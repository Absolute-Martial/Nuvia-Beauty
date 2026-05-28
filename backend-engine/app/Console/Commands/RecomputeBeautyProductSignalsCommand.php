<?php

namespace App\Console\Commands;

use App\Domains\Beauty\Services\BeautyProductSignalService;
use Illuminate\Console\Command;

class RecomputeBeautyProductSignalsCommand extends Command
{
    protected $signature = 'beauty:recompute-product-signals {--product_id=* : Limit recompute to specific product IDs}';

    protected $description = 'Recompute aggregated beauty product signals from recorded beauty events.';

    public function handle(BeautyProductSignalService $signals): int
    {
        $summary = $signals->recompute((array) $this->option('product_id'));

        $this->info(sprintf(
            'Recomputed %d beauty product signal row(s) using %s.',
            $summary['recomputed_count'],
            $summary['signal_version'],
        ));

        if (!empty($summary['product_ids'])) {
            $this->line('Products: ' . implode(', ', $summary['product_ids']));
        }

        return self::SUCCESS;
    }
}
