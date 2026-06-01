<?php

namespace App\Domains\Beauty\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PerfectCorpClient
{
    public function enabled(): bool
    {
        return (bool) config('services.perfect_corp.enabled', false);
    }

    public function demoMode(): bool
    {
        return (bool) config('services.perfect_corp.demo_mode', true);
    }

    public function pollIntervalSeconds(): int
    {
        return max(1, (int) config('services.perfect_corp.poll_interval_seconds', 2));
    }

    public function maxAttempts(): int
    {
        return max(1, (int) config('services.perfect_corp.max_attempts', 3));
    }

    public function selfCheck(): array
    {
        $baseUrl = trim((string) config('services.perfect_corp.base_url', ''));
        $hasBearerKey = trim((string) config('services.perfect_corp.bearer_key', '')) !== '';
        $hasApiKey = trim((string) config('services.perfect_corp.api_key', '')) !== '';

        if (!$this->enabled()) {
            return [
                'ok' => true,
                'status' => 'skipped',
                'message' => 'Perfect Corp live mode is disabled.',
            ];
        }

        if ($this->demoMode()) {
            return [
                'ok' => true,
                'status' => 'skipped',
                'message' => 'Perfect Corp demo mode is enabled; live connectivity probe skipped.',
            ];
        }

        if ($baseUrl === '') {
            return [
                'ok' => false,
                'status' => 'misconfigured',
                'message' => 'Perfect Corp API base URL is not configured.',
            ];
        }

        if (!$hasBearerKey && !$hasApiKey) {
            return [
                'ok' => false,
                'status' => 'misconfigured',
                'message' => 'Perfect Corp credentials are not configured.',
            ];
        }

        try {
            $response = $this->request()
                ->get('/');

            return [
                'ok' => $response->successful(),
                'status' => $response->successful() ? 'ok' : 'failed',
                'message' => $response->successful()
                    ? 'Perfect Corp connectivity probe succeeded.'
                    : 'Perfect Corp connectivity probe returned a non-success status.',
                'http_status' => $response->status(),
            ];
        } catch (ConnectionException|RequestException $exception) {
            return [
                'ok' => false,
                'status' => 'unreachable',
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function submitSkinAnalysis(array $payload): array
    {
        $response = $this->request()
            ->post('/skin-analysis/tasks', $payload)
            ->throw()
            ->json();

        return is_array($response) ? $response : [];
    }

    public function fetchTaskStatus(string $providerTaskId): array
    {
        $response = $this->request()
            ->get("/skin-analysis/tasks/{$providerTaskId}")
            ->throw()
            ->json();

        return is_array($response) ? $response : [];
    }

    protected function request(): PendingRequest
    {
        $baseUrl = trim((string) config('services.perfect_corp.base_url', ''));

        if (!$this->enabled() || $this->demoMode()) {
            throw new RuntimeException('Perfect Corp live mode is disabled.');
        }

        if ($baseUrl === '') {
            throw new RuntimeException('Perfect Corp API base URL is not configured.');
        }

        $request = Http::baseUrl(rtrim($baseUrl, '/'))
            ->acceptJson()
            ->timeout((int) config('services.perfect_corp.timeout_seconds', 120));

        $bearerKey = trim((string) config('services.perfect_corp.bearer_key', ''));
        $apiKey = trim((string) config('services.perfect_corp.api_key', ''));

        if ($bearerKey !== '') {
            $request = $request->withToken($bearerKey);
        }

        if ($apiKey !== '') {
            $request = $request->withHeaders([
                'x-api-key' => $apiKey,
            ]);
        }

        if ($bearerKey === '' && $apiKey === '') {
            throw new RuntimeException('Perfect Corp credentials are not configured.');
        }

        return $request;
    }
}
