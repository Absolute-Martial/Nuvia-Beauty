<?php

namespace App\Domains\Storage\Services;

use Aws\S3\S3Client;
use DateTimeInterface;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class S3CompatibleStorageService
{
    public function diskConfig(string $diskName): array
    {
        $config = config("filesystems.disks.{$diskName}");

        if (!is_array($config) || ($config['driver'] ?? null) !== 's3') {
            throw new RuntimeException("Disk [{$diskName}] is not configured as an S3-compatible disk.");
        }

        return $config;
    }

    public function provider(): string
    {
        return (string) config('filesystems.s3_compatible.provider', 'generic');
    }

    public function bucket(string $diskName): string
    {
        return (string) $this->diskConfig($diskName)['bucket'];
    }

    public function temporaryUploadUrl(
        string $diskName,
        string $objectKey,
        string $contentType,
        DateTimeInterface $expiresAt,
    ): array {
        $config = $this->diskConfig($diskName);
        $client = $this->client($config);
        $command = $client->getCommand('PutObject', [
            'Bucket' => $config['bucket'],
            'Key' => $objectKey,
            'ContentType' => $contentType,
        ]);

        $request = $client->createPresignedRequest($command, $expiresAt);

        return [
            'url' => (string) $request->getUri(),
            'method' => $request->getMethod(),
            'headers' => [
                'Content-Type' => $contentType,
            ],
        ];
    }

    public function temporaryDownloadUrl(string $diskName, string $objectKey, DateTimeInterface $expiresAt): string
    {
        $config = $this->diskConfig($diskName);
        $client = $this->client($config);
        $command = $client->getCommand('GetObject', [
            'Bucket' => $config['bucket'],
            'Key' => $objectKey,
        ]);

        return (string) $client->createPresignedRequest($command, $expiresAt)->getUri();
    }

    public function objectExists(string $diskName, string $objectKey): bool
    {
        return Storage::disk($diskName)->exists($objectKey);
    }

    public function deleteObject(string $diskName, string $objectKey): bool
    {
        return Storage::disk($diskName)->delete($objectKey);
    }

    protected function client(array $config): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => $config['region'],
            'endpoint' => $config['endpoint'] ?? null,
            'use_path_style_endpoint' => (bool) ($config['use_path_style_endpoint'] ?? false),
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);
    }
}
