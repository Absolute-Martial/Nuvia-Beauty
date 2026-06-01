<?php

namespace Tests\Unit\Beauty;

use App\Domains\Beauty\Services\PerfectCorpClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PerfectCorpClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.perfect_corp.base_url', 'https://provider.example.test');
        Config::set('services.perfect_corp.api_key', 'test-api-key');
        Config::set('services.perfect_corp.bearer_key', 'test-bearer-key');
        Config::set('services.perfect_corp.timeout_seconds', 45);
        Config::set('services.perfect_corp.poll_interval_seconds', 2);
        Config::set('services.perfect_corp.max_attempts', 3);
    }

    public function test_self_check_skips_when_provider_is_disabled(): void
    {
        Config::set('services.perfect_corp.enabled', false);
        Config::set('services.perfect_corp.demo_mode', true);

        Http::fake();

        $client = app(PerfectCorpClient::class);
        $status = $client->selfCheck();

        $this->assertTrue($status['ok']);
        $this->assertSame('skipped', $status['status']);
        Http::assertNothingSent();
    }

    public function test_self_check_reports_missing_credentials_for_live_mode(): void
    {
        Config::set('services.perfect_corp.enabled', true);
        Config::set('services.perfect_corp.demo_mode', false);
        Config::set('services.perfect_corp.api_key', '');
        Config::set('services.perfect_corp.bearer_key', '');

        Http::fake();

        $client = app(PerfectCorpClient::class);
        $status = $client->selfCheck();

        $this->assertFalse($status['ok']);
        $this->assertSame('misconfigured', $status['status']);
        Http::assertNothingSent();
    }

    public function test_self_check_probes_provider_when_live_mode_is_enabled(): void
    {
        Config::set('services.perfect_corp.enabled', true);
        Config::set('services.perfect_corp.demo_mode', false);

        Http::fake([
            'https://provider.example.test/' => Http::response(['ok' => true], 200),
        ]);

        $client = app(PerfectCorpClient::class);
        $status = $client->selfCheck();

        $this->assertTrue($status['ok']);
        $this->assertSame('ok', $status['status']);
        $this->assertSame(200, $status['http_status']);
        Http::assertSentCount(1);
    }

    public function test_submit_skin_analysis_uses_expected_auth_headers(): void
    {
        Config::set('services.perfect_corp.enabled', true);
        Config::set('services.perfect_corp.demo_mode', false);

        Http::fake(function (Request $request) {
            $this->assertSame('https://provider.example.test/skin-analysis/tasks', (string) $request->url());
            $this->assertSame('Bearer test-bearer-key', $request->header('Authorization')[0] ?? null);
            $this->assertSame('test-api-key', $request->header('x-api-key')[0] ?? null);
            $this->assertSame(['session_id' => 'session-123'], $request->data());

            return Http::response(['task_id' => 'task-123'], 200);
        });

        $client = app(PerfectCorpClient::class);
        $response = $client->submitSkinAnalysis(['session_id' => 'session-123']);

        $this->assertSame('task-123', $response['task_id']);
    }

    public function test_fetch_task_status_returns_provider_payload(): void
    {
        Config::set('services.perfect_corp.enabled', true);
        Config::set('services.perfect_corp.demo_mode', false);

        Http::fake([
            'https://provider.example.test/skin-analysis/tasks/task-123' => Http::response([
                'id' => 'task-123',
                'status' => 'completed',
            ], 200),
        ]);

        $client = app(PerfectCorpClient::class);
        $response = $client->fetchTaskStatus('task-123');

        $this->assertSame('task-123', $response['id']);
        $this->assertSame('completed', $response['status']);
    }
}
