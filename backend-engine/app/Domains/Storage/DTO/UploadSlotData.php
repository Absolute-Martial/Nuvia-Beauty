<?php

namespace App\Domains\Storage\DTO;

class UploadSlotData
{
    public function __construct(
        public readonly int $mediaId,
        public readonly StoredObjectRef $objectRef,
        public readonly string $uploadUrl,
        public readonly string $method,
        public readonly array $headers,
        public readonly string $expiresAt,
    ) {
    }

    public function toArray(): array
    {
        return [
            'media_id' => $this->mediaId,
            'storage_provider' => $this->objectRef->storageProvider,
            'disk_name' => $this->objectRef->diskName,
            'bucket' => $this->objectRef->bucket,
            'object_key' => $this->objectRef->objectKey,
            'object_version' => $this->objectRef->objectVersion,
            'upload_url' => $this->uploadUrl,
            'method' => $this->method,
            'headers' => $this->headers,
            'expires_at' => $this->expiresAt,
        ];
    }
}
