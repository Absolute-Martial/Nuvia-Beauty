<?php

namespace Marvel\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Marvel\Support\DefaultSettings;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $language = DEFAULT_LANGUAGE ?? "en";
        $now = Carbon::now();

        // run your app seeder
        DB::table('settings')->updateOrInsert([
            'language' => $language,
        ], [
            'options' => json_encode(DefaultSettings::defaults($language)),
            "language" => $language,
            "created_at" => $now,
            "updated_at" => $now,
        ]);
    }
}
