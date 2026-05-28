<?php

namespace App\Console\Commands;

use App\Domains\Beauty\Services\KaggleBeautyCatalogImportService;
use Illuminate\Console\Command;

class BeautySeedKaggleCatalogCommand extends Command
{
    protected $signature = 'beauty:seed-kaggle-catalog
        {source : Path to a Kaggle CSV file or ZIP archive}
        {--limit=40 : Maximum number of existing products to map}
        {--report-path= : Optional JSON report path; defaults to storage/app/beauty/kaggle-import-report.json}
        {--dry-run : Parse and preview the import without writing mappings}';

    protected $description = 'Seed beauty mappings for the existing catalog from a Kaggle cosmetic products dataset.';

    public function handle(KaggleBeautyCatalogImportService $importer): int
    {
        $source = (string) $this->argument('source');
        $limit = max(1, (int) $this->option('limit'));
        $reportPath = (string) ($this->option('report-path') ?: storage_path('app/beauty/kaggle-import-report.json'));
        $dryRun = (bool) $this->option('dry-run');

        $summary = $importer->import($source, $limit, $dryRun);
        $summary['dry_run'] = $dryRun;
        $summary['report_path'] = $reportPath;

        if (!$dryRun) {
            $importer->writeReport($summary, $reportPath);
        }

        $this->info(sprintf(
            'Processed %d dataset row(s) and mapped %d existing product(s).',
            $summary['dataset_rows'],
            $summary['products_targeted'],
        ));

        $this->line('Source: ' . $summary['source_path']);
        $this->line('Mappings written: ' . $summary['mappings_written']);

        if (!empty($summary['family_counts'])) {
            $this->line('Catalog families: ' . json_encode($summary['family_counts'], JSON_UNESCAPED_SLASHES));
        }

        if ($dryRun) {
            $this->warn('Dry run enabled: no mappings were written.');
        } else {
            $this->info('Import report written to ' . $reportPath);
        }

        return self::SUCCESS;
    }
}
