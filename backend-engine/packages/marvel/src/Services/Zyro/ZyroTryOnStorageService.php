<?php

namespace Marvel\Services\Zyro;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ZyroTryOnStorageService
{
    public function storeSourcePhoto(UploadedFile $photo, int|string|null $userId): array
    {
        $extension = $photo->guessExtension() ?: $photo->extension() ?: 'jpg';
        $photoId = (string) Str::uuid();
        $directory = storage_path('app/private/zyro/try-on/source-photos/' . now()->format('Y/m'));
        $filename = $photoId . '.' . $extension;

        File::ensureDirectoryExists($directory);
        $photo->move($directory, $filename);

        $fullPath = $directory . DIRECTORY_SEPARATOR . $filename;

        $metadata = [
            'photo_id' => $photoId,
            'user_id' => $userId,
            'path' => $fullPath,
            'mime_type' => File::mimeType($fullPath),
            'file_name' => $filename,
            'file_size' => File::size($fullPath),
            'created_at' => now()->toISOString(),
        ];

        File::put($this->sourcePhotoMetadataPath($photoId), json_encode($metadata, JSON_PRETTY_PRINT));

        return $metadata;
    }

    public function getSourcePhoto(string $photoId): ?array
    {
        $path = $this->sourcePhotoMetadataPath($photoId);

        if (!File::exists($path)) {
            return null;
        }

        $metadata = json_decode(File::get($path), true);
        if (!is_array($metadata)) {
            return null;
        }

        $filePath = $metadata['path'] ?? null;
        if (!is_string($filePath) || !File::exists($filePath)) {
            return null;
        }

        return $metadata;
    }

    public function putTask(array $task): array
    {
        $taskId = $task['task_id'] ?? (string) Str::uuid();
        $directory = storage_path('app/private/zyro/try-on/results');
        File::ensureDirectoryExists($directory);

        $task = array_merge($task, [
            'task_id' => $taskId,
            'updated_at' => now()->toISOString(),
        ]);

        File::put($this->taskPath($taskId), json_encode($task, JSON_PRETTY_PRINT));
        return $task;
    }

    public function getTask(string $taskId): ?array
    {
        $path = $this->taskPath($taskId);
        if (!File::exists($path)) {
            return null;
        }

        $task = json_decode(File::get($path), true);
        return is_array($task) ? $task : null;
    }

    private function taskPath(string $taskId): string
    {
        return storage_path('app/private/zyro/try-on/results/' . $taskId . '.json');
    }

    private function sourcePhotoMetadataPath(string $photoId): string
    {
        $directory = storage_path('app/private/zyro/try-on/source-photos');
        File::ensureDirectoryExists($directory);

        return $directory . DIRECTORY_SEPARATOR . $photoId . '.json';
    }
}
