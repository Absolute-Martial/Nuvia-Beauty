<?php

namespace Marvel\Services\Zyro;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ZyroSettingsService
{
    private const CACHE_KEY = 'zyro.settings';

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $settingsPath = $this->settingsPath();

            if (!File::exists($settingsPath)) {
                return $this->defaults();
            }

            $settings = json_decode(File::get($settingsPath), true);

            if (!is_array($settings)) {
                return $this->defaults();
            }

            return array_replace_recursive($this->defaults(), $settings);
        });
    }

    public function settingsPath(): string
    {
        return storage_path('app/private/zyro/settings/zyro.settings.json');
    }

    private function defaults(): array
    {
        return [
            'youcam' => [
                'apiFamily' => 'clothes-vto-v3',
                'maxUploadMb' => 10,
                'allowedMimeTypes' => ['image/jpeg', 'image/png'],
                'pollIntervalMs' => 3000,
                'timeoutSeconds' => 120,
            ],
            'featureFlags' => [
                'enablePhotoTryOn' => true,
                'enableLiveCameraTryOn' => false,
                'enablePayment' => false,
                'enableCheckout' => false,
                'enablePaidOrders' => false,
                'enableShipping' => false,
            ],
        ];
    }
}
