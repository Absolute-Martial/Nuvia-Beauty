<?php

namespace App\Domains\Beauty\Jobs;

use App\Domains\Beauty\Services\BeautyProductSignalService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecomputeBeautyProductSignals
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public array $productIds = [],
    ) {
    }

    public function handle(BeautyProductSignalService $signals): array
    {
        return $signals->recompute($this->productIds);
    }
}
