<?php

namespace Marvel\Services\Zyro;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ZyroYouCamService
{
    private const ENDPOINTS = [
        'clothes' => [
            'file' => '/s2s/v2.0/file/cloth-v3',
            'task' => '/s2s/v3.0/task/cloth',
            'task_status' => '/s2s/v3.0/task/cloth',
        ],
        'watch' => [
            'file' => '/s2s/v2.0/file/2d-vto/watch',
            'task' => '/s2s/v2.0/task/2d-vto/watch',
            'task_status' => '/s2s/v2.0/task/2d-vto/watch',
        ],
        'shoes' => [
            'file' => '/s2s/v2.0/file/shoes',
            'task' => '/s2s/v2.0/task/shoes',
            'task_status' => '/s2s/v2.0/task/shoes',
        ],
        'bags' => [
            'file' => '/s2s/v2.0/file/bag',
            'task' => '/s2s/v2.0/task/bag',
            'task_status' => '/s2s/v2.0/task/bag',
        ],
    ];

    public function configured(): bool
    {
        return filled($this->authToken()) && filled(env('YOUCAM_API_BASE_URL'));
    }

    public function createUploadedSourceFile(array $photo, string $type = 'clothes'): string
    {
        if (!isset(self::ENDPOINTS[$type])) {
            throw new \InvalidArgumentException("Invalid VTO type: {$type}");
        }

        $fileResponse = $this->api()
            ->post($this->url(self::ENDPOINTS[$type]['file']), [
                'files' => [[
                    'content_type' => $photo['mime_type'],
                    'file_name' => $photo['file_name'],
                    'file_size' => $photo['file_size'],
                ]],
            ])
            ->throw()
            ->json();

        $file = $fileResponse['data']['files'][0] ?? null;
        $request = $file['requests'][0] ?? null;

        if (!$file || !$request || empty($request['url'])) {
            throw new \RuntimeException('YouCam did not return an upload URL.');
        }

        Http::withHeaders($request['headers'] ?? [])
            ->withBody(File::get($photo['path']), $photo['mime_type'])
            ->put($request['url'])
            ->throw();

        return $file['file_id'];
    }

    public function createTask(string $type, array $payload): array
    {
        if (!isset(self::ENDPOINTS[$type])) {
            throw new \InvalidArgumentException("Invalid VTO type: {$type}");
        }

        return $this->api()
            ->post($this->url(self::ENDPOINTS[$type]['task']), $payload)
            ->throw()
            ->json();
    }

    public function getTask(string $type, string $remoteTaskId): array
    {
        if (!isset(self::ENDPOINTS[$type])) {
            throw new \InvalidArgumentException("Invalid VTO type: {$type}");
        }

        return $this->api()
            ->get($this->url(self::ENDPOINTS[$type]['task_status'] . '/' . $remoteTaskId))
            ->throw()
            ->json();
    }

    public function storeResultImage(string $taskId, string $resultUrl): string
    {
        $response = Http::timeout(60)->get($resultUrl)->throw();
        $directory = storage_path('app/private/zyro/try-on/results');
        $path = $directory . DIRECTORY_SEPARATOR . $taskId . '.jpg';

        File::ensureDirectoryExists($directory);
        File::put($path, $response->body());

        return $path;
    }

    private function api()
    {
        return Http::withToken($this->authToken())
            ->acceptJson()
            ->asJson()
            ->timeout(120);
    }

    private function authToken(): string
    {
        return (string) (env('YOUCAM_API_KEY') ?: env('YOUCAM_API_BEARER_KEY'));
    }

    private function url(string $path): string
    {
        return rtrim((string) env('YOUCAM_API_BASE_URL'), '/') . $path;
    }
}
