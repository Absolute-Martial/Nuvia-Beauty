<?php

namespace Marvel\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Marvel\Exceptions\MarvelException;

class CoreController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function importFilesystemDisk(): string
    {
        return (string) config('filesystems.imports_disk', config('filesystems.default'));
    }

    protected function withStoredImportFile(UploadedFile $uploadedFile, string $storedFileName, callable $callback)
    {
        $disk = $this->importFilesystemDisk();
        $storedPath = $uploadedFile->storePubliclyAs('csv-files', $storedFileName, $disk);
        $tempPath = tempnam(sys_get_temp_dir(), 'nuvia-import-');

        if ($tempPath === false) {
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }

        $stream = Storage::disk($disk)->readStream($storedPath);
        $tempHandle = fopen($tempPath, 'wb');

        if ($stream === false || $tempHandle === false) {
            if (is_resource($stream)) {
                fclose($stream);
            }

            if (is_resource($tempHandle)) {
                fclose($tempHandle);
            }

            @unlink($tempPath);

            throw new MarvelException(SOMETHING_WENT_WRONG);
        }

        stream_copy_to_stream($stream, $tempHandle);
        fclose($stream);
        fclose($tempHandle);

        try {
            return $callback($tempPath, $storedPath, $disk);
        } finally {
            @unlink($tempPath);
        }
    }
}
