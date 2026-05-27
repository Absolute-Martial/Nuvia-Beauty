<?php

namespace App\Domains\Storage\Services;

use App\Domains\Storage\DTO\StoredObjectRef;
use App\Domains\Storage\DTO\UploadSlotData;
use App\Domains\Storage\Jobs\DeleteExpiredMediaAssets;
use App\Domains\Storage\Models\MediaAsset;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Marvel\Database\Models\Shop;
use Marvel\Database\Models\User;
use Marvel\Enums\Permission;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MediaAssetService
{
    public const PURPOSE_DISK_MAP = [
        'public_asset' => 's3_public',
        'beauty_input' => 's3_beauty_inputs',
        'beauty_result' => 's3_beauty_results',
        'beauty_calibration' => 's3_beauty_calibration',
    ];

    public function __construct(
        protected S3CompatibleStorageService $storage,
    ) {
    }

    public function createUploadSlot(array $payload, User $actor): UploadSlotData
    {
        $diskName = $this->resolveDiskName($payload['purpose']);
        $ownerContext = $this->resolveOwnerContext($payload, $actor);
        $expiresAt = CarbonImmutable::now()->addMinutes((int) config('filesystems.s3_compatible.upload_url_ttl_minutes', 15));

        $mediaAsset = MediaAsset::create([
            'session_id' => $payload['session_id'] ?? null,
            'profile_id' => $ownerContext['profile_id'],
            'shop_id' => $ownerContext['shop_id'],
            'owner_type' => $ownerContext['owner_type'],
            'owner_id' => $ownerContext['owner_id'],
            'asset_type' => $payload['asset_type'],
            'storage_provider' => $this->storage->provider(),
            'disk_name' => $diskName,
            'bucket' => $this->storage->bucket($diskName),
            'object_key' => '',
            'object_version' => null,
            'content_type' => $payload['content_type'],
            'size_bytes' => $payload['size_bytes'],
            'checksum_sha256' => $payload['checksum_sha256'] ?? null,
            'visibility' => $diskName === 's3_public' ? 'public' : 'private',
            'status' => 'pending_upload',
            'expires_at' => in_array($payload['purpose'], ['beauty_input', 'beauty_result', 'beauty_calibration'], true)
                ? CarbonImmutable::now()->addDays(7)
                : null,
        ]);

        $objectKey = $this->buildObjectKey($mediaAsset, $payload['file_name'], $payload['purpose']);
        $mediaAsset->forceFill(['object_key' => $objectKey])->save();

        $presignedUpload = $this->storage->temporaryUploadUrl(
            $diskName,
            $objectKey,
            $payload['content_type'],
            $expiresAt,
        );

        return new UploadSlotData(
            $mediaAsset->id,
            new StoredObjectRef(
                storageProvider: $mediaAsset->storage_provider,
                diskName: $diskName,
                bucket: $mediaAsset->bucket,
                objectKey: $objectKey,
            ),
            $presignedUpload['url'],
            $presignedUpload['method'],
            $presignedUpload['headers'],
            $expiresAt->toIso8601String(),
        );
    }

    public function confirmUpload(MediaAsset $mediaAsset): MediaAsset
    {
        if (!$this->storage->objectExists($mediaAsset->disk_name, $mediaAsset->object_key)) {
            throw new \RuntimeException('Uploaded object was not found in S3-compatible storage.');
        }

        $mediaAsset->forceFill([
            'status' => 'confirmed',
        ])->save();

        return $mediaAsset->fresh();
    }

    public function createDownloadUrl(MediaAsset $mediaAsset): array
    {
        $expiresAt = CarbonImmutable::now()->addMinutes((int) config('filesystems.s3_compatible.download_url_ttl_minutes', 60));

        return [
            'url' => $this->storage->temporaryDownloadUrl($mediaAsset->disk_name, $mediaAsset->object_key, $expiresAt),
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    public function discard(MediaAsset $mediaAsset): MediaAsset
    {
        $mediaAsset->forceFill([
            'status' => 'discarded',
            'discarded_at' => now(),
        ])->save();

        DeleteExpiredMediaAssets::dispatch([$mediaAsset->id]);

        return $mediaAsset->fresh();
    }

    public function resolveDiskName(string $purpose): string
    {
        $diskName = self::PURPOSE_DISK_MAP[$purpose] ?? null;

        if (!$diskName) {
            throw new \InvalidArgumentException("Unsupported storage purpose [{$purpose}].");
        }

        return $diskName;
    }

    public function resolveOwnerContext(array $payload, User $actor): array
    {
        $ownerType = $payload['owner_type'];
        $ownerId = (int) $payload['owner_id'];
        $profileId = isset($payload['profile_id']) ? (int) $payload['profile_id'] : null;
        $shopId = isset($payload['shop_id']) ? (int) $payload['shop_id'] : null;

        if ($ownerType === 'user' && $ownerId !== (int) $actor->id && !$actor->hasPermissionTo(Permission::SUPER_ADMIN)) {
            throw new AccessDeniedHttpException('You can only create uploads for your own user record.');
        }

        if ($ownerType === 'profile' && $profileId !== (int) optional($actor->profile)->id && !$actor->hasPermissionTo(Permission::SUPER_ADMIN)) {
            throw new AccessDeniedHttpException('You can only create uploads for your own profile.');
        }

        if ($ownerType === 'shop') {
            if (!$shopId || !$this->canManageShop($actor, $shopId)) {
                throw new AccessDeniedHttpException('You do not have permission to manage that shop.');
            }
        }

        return [
            'owner_type' => $ownerType,
            'owner_id' => match ($ownerType) {
                'profile' => $profileId,
                'shop' => $shopId,
                default => $ownerId,
            },
            'profile_id' => $profileId,
            'shop_id' => $shopId,
        ];
    }

    protected function buildObjectKey(MediaAsset $mediaAsset, string $fileName, string $purpose): string
    {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION) ?: 'bin');
        $sessionKey = $mediaAsset->session_id ?: 'anonymous';

        return match ($purpose) {
            'public_asset' => sprintf(
                'public/products/%s/%s/%s.%s',
                $mediaAsset->shop_id ?: 'global',
                $mediaAsset->owner_id,
                Str::uuid(),
                $extension
            ),
            'beauty_input' => sprintf(
                'beauty/inputs/%s/%s/%s/source.%s',
                $mediaAsset->shop_id ?: 'global',
                $sessionKey,
                $mediaAsset->id,
                $extension
            ),
            'beauty_result' => sprintf(
                'beauty/results/%s/%s/%s/result.%s',
                $mediaAsset->shop_id ?: 'global',
                $sessionKey,
                $mediaAsset->id,
                $extension
            ),
            'beauty_calibration' => sprintf(
                'beauty/calibration/%s/%s/calibration.%s',
                $mediaAsset->profile_id ?: $mediaAsset->owner_id,
                $mediaAsset->id,
                $extension
            ),
            default => throw new \InvalidArgumentException("Unsupported storage purpose [{$purpose}]."),
        };
    }

    protected function canManageShop(User $actor, int $shopId): bool
    {
        if ($actor->hasPermissionTo(Permission::SUPER_ADMIN)) {
            return true;
        }

        $shop = Shop::with('staffs')->find($shopId);

        if (!$shop) {
            return false;
        }

        if ($actor->hasPermissionTo(Permission::STORE_OWNER) && (int) $shop->owner_id === (int) $actor->id) {
            return true;
        }

        return $actor->hasPermissionTo(Permission::STAFF) && $shop->staffs->contains('id', $actor->id);
    }
}
