<?php

namespace App\Console\Commands;

use App\Domains\Beauty\Services\BeautyDemoPreparationService;
use Illuminate\Console\Command;

class BeautyPrepareDemoCommand extends Command
{
    protected $signature = 'beauty:prepare-demo
        {--kaggle-source= : Optional Kaggle CSV or ZIP path for catalog-backed mapping enrichment}
        {--shop-id= : Optional demo shop override}
        {--consultant-user-id= : Optional consultant user override}
        {--mapping-limit=40 : Max products to import when a Kaggle source is provided}
        {--allow-production : Explicitly allow demo preparation while APP_ENV=production}
        {--report-path= : Optional JSON report path}';

    protected $description = 'Prepare the deterministic Phase 6 demo dataset, consultation session, and analysis result.';

    public function handle(BeautyDemoPreparationService $demo): int
    {
        $summary = $demo->prepare([
            'kaggle_source' => $this->option('kaggle-source'),
            'shop_id' => $this->option('shop-id') !== null ? (int) $this->option('shop-id') : null,
            'consultant_user_id' => $this->option('consultant-user-id') !== null ? (int) $this->option('consultant-user-id') : null,
            'mapping_limit' => (int) $this->option('mapping-limit'),
            'allow_production' => (bool) $this->option('allow-production'),
            'report_path' => $this->option('report-path') ?: null,
        ]);

        $this->info(sprintf(
            'Prepared Phase 6 demo data for shop #%d (%s).',
            $summary['shop']['id'],
            $summary['shop']['name'] ?? 'Unnamed shop'
        ));
        $this->line('Consultant: ' . $summary['consultant']['email']);
        $this->line('Demo session: ' . $summary['session_summary']['public_id']);
        $this->line('Recommendations: ' . $summary['session_summary']['recommendation_count']);
        $this->line('Strong recommendations: ' . $summary['session_summary']['strong_recommendations']);
        $this->line('Warning recommendations: ' . $summary['session_summary']['warning_recommendations']);
        $this->line('Report: ' . $summary['report_path']);

        if (!empty($summary['kaggle_import'])) {
            $this->line('Kaggle source processed: ' . $summary['kaggle_import']['source_path']);
        }

        return self::SUCCESS;
    }
}
