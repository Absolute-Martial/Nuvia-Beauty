<?php

namespace App\Domains\Storage\Jobs;

use App\Domains\Storage\Models\MediaAsset;
use App\Domains\Storage\Services\S3CompatibleStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteExpiredMediaAssets implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected ?array $mediaIds = null,
    ) {
    }

    public function handle(S3CompatibleStorageService $storage): void
    {
        $query = MediaAsset::query()
            ->whereIn('status', ['discarded', 'expired', 'delete_failed']);

        if ($this->mediaIds) {
            $query->whereIn('id', $this->mediaIds);
        } else {
            $query->where(function ($builder) {
                $builder->whereNotNull('discarded_at')
                    ->orWhere(function ($nested) {
                        $nested->whereNotNull('expires_at')->where('expires_at', '<=', now());
                    });
            });
        }

        $query->chunkById(50, function ($assets) use ($storage) {
            foreach ($assets as $asset) {
                try {
                    $storage->deleteObject($asset->disk_name, $asset->object_key);
                    $asset->forceFill([
                        'status' => 'deleted',
                    ])->delete();
                } catch (\Throwable $exception) {
                    $asset->forceFill([
                        'status' => 'delete_failed',
                    ])->save();
                }
            }
        });
    }
}
