<?php

namespace Marvel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\{confirm, info};

class SettingsDataImporter extends Command
{
    protected $signature = 'marvel:settings-seed';

    protected $description = 'Import Settings Data';

    public function handle()
    {
        if (DB::table('settings')->where('id', 1)->exists()) {

            if (confirm('Already data exists. Do you want to refresh it with dummy settings?')) {

                info('Seeding necessary settings....');

                DB::table('settings')->truncate();

                info('Importing dummy settings...');

                $this->call('db:seed', [
                    '--class' => '\\Marvel\\Database\\Seeders\\SettingsSeeder'
                ]);

                info('Settings were imported successfully');
            } else {
                info('Previous settings was kept. Thanks!');
            }
        }
    }
}
