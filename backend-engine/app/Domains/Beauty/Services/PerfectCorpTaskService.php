<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\Jobs\PollPerfectCorpAnalysisTask;
use App\Domains\Beauty\Models\BeautyAiTask;
use Illuminate\Support\Arr;
use RuntimeException;

class PerfectCorpTaskService
{
    public const PROVIDER = 'perfect_corp_skin_analysis';
    public const TASK_TYPE = 'skin_analysis';

    public function __construct(
        protected BeautySessionService $sessions,
        protected NormalizePerfectCorpResultService $normalizer,
        protected PerfectCorpClient $client,
    ) {
    }

    public function processCreateTask(int $taskId): void
    {
        $task = BeautyAiTask::with('beautySession.currentSnapshot')->findOrFail($taskId);
        $session = $task->beautySession;

        if (!$session) {
            throw new RuntimeException('The consultation session for the analysis task could not be resolved.');
        }

        $task->forceFill([
            'status' => 'processing',
            'started_at' => now(),
            'error_message' => null,
        ])->save();

        if ($this->client->demoMode()) {
            $normalized = $this->normalizer->demoResult($session);

            $this->sessions->completeAnalysisTask(
                $task,
                $normalized['summary_payload'],
                $normalized['normalized_traits'],
                [
                    'mode' => 'demo',
                    'seed' => substr(md5($session->public_id), 0, 8),
                ],
                false,
            );

            return;
        }

        if (!$this->client->enabled()) {
            $this->sessions->failAnalysisTask(
                $task,
                'Perfect Corp live mode is disabled. Enable live credentials or keep demo mode on.'
            );

            return;
        }

        $payload = [
            'session_id' => $session->public_id,
            'media' => [
                'disk_name' => $session->primaryMediaAsset?->disk_name,
                'bucket' => $session->primaryMediaAsset?->bucket,
                'object_key' => $session->primaryMediaAsset?->object_key,
                'content_type' => $session->primaryMediaAsset?->content_type,
            ],
            'traits' => $this->sessions->snapshotTraitsForAnalysis($session),
        ];

        $providerResponse = $this->client->submitSkinAnalysis($payload);
        $providerTaskId = (string) (
            Arr::get($providerResponse, 'task_id')
            ?? Arr::get($providerResponse, 'id')
            ?? ''
        );

        if ($providerTaskId === '') {
            $this->sessions->failAnalysisTask(
                $task,
                'Perfect Corp did not return a provider task identifier.',
                $providerResponse,
            );

            return;
        }

        $task->forceFill([
            'provider_task_id' => $providerTaskId,
            'response_payload' => $providerResponse,
        ])->save();

        PollPerfectCorpAnalysisTask::dispatch($task->id, 1)
            ->delay(now()->addSeconds($this->client->pollIntervalSeconds()));
    }

    public function processPollTask(int $taskId, int $attempt): void
    {
        $task = BeautyAiTask::with('beautySession.currentSnapshot')->findOrFail($taskId);

        if ($this->client->demoMode()) {
            return;
        }

        if (!$this->client->enabled()) {
            $this->sessions->failAnalysisTask(
                $task,
                'Perfect Corp live mode is disabled during polling.'
            );

            return;
        }

        if (empty($task->provider_task_id)) {
            $this->sessions->failAnalysisTask(
                $task,
                'Perfect Corp polling cannot continue without a provider task ID.'
            );

            return;
        }

        $providerResponse = $this->client->fetchTaskStatus($task->provider_task_id);
        $status = strtolower((string) (
            Arr::get($providerResponse, 'status')
            ?? Arr::get($providerResponse, 'task.status')
            ?? 'processing'
        ));

        if (in_array($status, ['completed', 'done', 'success', 'succeeded'], true)) {
            $session = $task->beautySession;

            if (!$session) {
                throw new RuntimeException('The consultation session for the polling task could not be resolved.');
            }

            $normalized = $this->normalizer->liveResult($providerResponse, $session);

            $this->sessions->completeAnalysisTask(
                $task,
                $normalized['summary_payload'],
                $normalized['normalized_traits'],
                $providerResponse,
                true,
            );

            return;
        }

        if (in_array($status, ['failed', 'error', 'cancelled'], true)) {
            $this->sessions->failAnalysisTask(
                $task,
                (string) (Arr::get($providerResponse, 'message') ?? 'Perfect Corp reported a failed analysis task.'),
                $providerResponse,
            );

            return;
        }

        if ($attempt >= $this->client->maxAttempts()) {
            $this->sessions->failAnalysisTask(
                $task,
                'Perfect Corp analysis did not complete before the maximum polling attempts were exhausted.',
                $providerResponse,
            );

            return;
        }

        $task->forceFill([
            'status' => 'processing',
            'response_payload' => $providerResponse,
        ])->save();

        PollPerfectCorpAnalysisTask::dispatch($task->id, $attempt + 1)
            ->delay(now()->addSeconds($this->client->pollIntervalSeconds()));
    }
}
