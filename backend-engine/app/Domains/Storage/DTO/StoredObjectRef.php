<?php

namespace App\Domains\Storage\DTO;

class StoredObjectRef
{
    public function __construct(
        public readonly string $storageProvider,
        public readonly string $diskName,
        public readonly string $bucket,
        public readonly string $objectKey,
        public readonly ?string $objectVersion = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'storage_provider' => $this->storageProvider,
            'disk_name' => $this->diskName,
            'bucket' => $this->bucket,
            'object_key' => $this->objectKey,
            'object_version' => $this->objectVersion,
        ];
    }
}
