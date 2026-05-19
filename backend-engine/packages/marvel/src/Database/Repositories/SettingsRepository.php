<?php


namespace Marvel\Database\Repositories;

use Carbon\Carbon;
use Marvel\Database\Models\Settings;

class SettingsRepository extends BaseRepository
{
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Settings::class;
    }

    public function getApplicationSettings(): array
    {
        return [
            'app_settings' => [
                'last_checking_time' => Carbon::now(),
                'trust' => true,
            ],
        ];
    }
}
