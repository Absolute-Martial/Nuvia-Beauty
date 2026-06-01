<?php

namespace App\Console\Commands;

use App\Domains\Beauty\Services\BeautyDemoPreparationService;
use Illuminate\Console\Command;

class BeautyAuditDemoReadinessCommand extends Command
{
    protected $signature = 'beauty:audit-demo-readiness
        {--shop-id= : Optional demo shop override}
        {--allow-production : Explicitly allow the audit while APP_ENV=production}
        {--report-path= : Optional JSON report path}';

    protected $description = 'Audit the Phase 6 demo-readiness surface and write a machine-readable report.';

    public function handle(BeautyDemoPreparationService $demo): int
    {
        $summary = $demo->audit([
            'shop_id' => $this->option('shop-id') !== null ? (int) $this->option('shop-id') : null,
            'allow_production' => (bool) $this->option('allow-production'),
            'report_path' => $this->option('report-path') ?: null,
        ]);

        foreach ($summary['checks'] as $check) {
            $line = sprintf(
                '[%s] %s',
                $check['passed'] ? 'PASS' : 'FAIL',
                $check['key']
            );
            $this->line($line . ' - ' . $check['message']);
        }

        $this->line('Report: ' . $summary['report_path']);

        return $summary['passed'] ? self::SUCCESS : self::FAILURE;
    }
}
