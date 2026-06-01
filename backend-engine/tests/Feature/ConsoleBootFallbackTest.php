<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Marvel\Database\Models\Settings;
use Tests\TestCase;

class ConsoleBootFallbackTest extends TestCase
{
    public function test_route_list_boots_without_settings_table(): void
    {
        $exitCode = Artisan::call('route:list', [
            '--path' => 'api/v1',
        ]);
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('api/v1/storage/upload-slots', $output);
        $this->assertNotSame('', trim($output));
    }

    public function test_settings_fallback_returns_default_payload_in_console(): void
    {
        $settings = Settings::firstOrFallback('en');

        $this->assertSame('en', $settings->language);
        $this->assertIsArray($settings->options);
        $this->assertArrayHasKey('siteTitle', $settings->options);
        $this->assertArrayHasKey('logo', $settings->options);
    }
}
