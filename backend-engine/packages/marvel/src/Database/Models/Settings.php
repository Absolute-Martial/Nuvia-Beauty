<?php

namespace Marvel\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Marvel\Support\DefaultSettings;
use Throwable;

class Settings extends Model
{
    protected $table = 'settings';

    public $guarded = [];

    protected $casts = [
        'options'   => 'json',
    ];

    public static function getData($language = DEFAULT_LANGUAGE)
    {
        try {
            $data = static::where('language', $language)->first();

            if (!$data) {
                $data = static::where('language', DEFAULT_LANGUAGE)->first();
            }
        } catch (Throwable $exception) {
            if (!static::shouldUseFallback($exception)) {
                throw $exception;
            }

            $data = null;
        }

        return $data ?: static::fallback($language);
    }

    public static function firstOrFallback(?string $language = null): self
    {
        try {
            $data = static::query()->first();
        } catch (Throwable $exception) {
            if (!static::shouldUseFallback($exception)) {
                throw $exception;
            }

            $data = null;
        }

        return $data ?: static::fallback($language);
    }

    public static function fallback(?string $language = null): self
    {
        $language = $language ?: (defined('DEFAULT_LANGUAGE') ? DEFAULT_LANGUAGE : 'en');

        return new static([
            'language' => $language,
            'options' => DefaultSettings::defaults($language),
        ]);
    }

    protected static function shouldUseFallback(Throwable $exception): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return static::isSettingsTableUnavailable($exception);
    }

    protected static function isSettingsTableUnavailable(Throwable $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'Base table or view not found')
            || str_contains($message, 'no such table')
            || str_contains($message, '`settings`')
            || str_contains($message, 'relation "settings" does not exist');
    }
}
