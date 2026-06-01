<?php

namespace Tests\Feature\Storage;

use App\Domains\Storage\Models\MediaAsset;
use App\Domains\Storage\Services\S3CompatibleStorageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Marvel\Database\Models\User;
use Tests\TestCase;

class StorageLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('queue.default', 'sync');

        if (
            blank(config('filesystems.disks.s3_beauty_inputs.key')) ||
            blank(config('filesystems.disks.s3_beauty_inputs.secret')) ||
            blank(config('filesystems.disks.s3_beauty_inputs.endpoint'))
        ) {
            $this->markTestSkipped('S3 lifecycle tests require configured S3 test credentials and endpoint.');
        }
    }

    public function test_private_media_lifecycle_works_end_to_end(): void
    {
        $user = $this->createVerifiedUser();
        Sanctum::actingAs($user, [], 'sanctum');

        $binary = random_bytes(64);

        $slotResponse = $this->postJson('/api/v1/storage/upload-slots', [
            'purpose' => 'beauty_input',
            'asset_type' => 'consultation_input_image',
            'owner_type' => 'user',
            'owner_id' => $user->id,
            'file_name' => 'consultation.png',
            'content_type' => 'image/png',
            'size_bytes' => strlen($binary),
        ])->assertCreated();

        $uploadData = $slotResponse->json('data');
        $mediaId = (int) $uploadData['media_id'];

        $uploadResponse = Http::withHeaders($uploadData['headers'])
            ->withBody($binary, 'image/png')
            ->send($uploadData['method'], $uploadData['upload_url']);

        $this->assertTrue(
            in_array($uploadResponse->status(), [200, 204], true),
            'Expected presigned upload to succeed.'
        );

        $media = $this->postJson("/api/v1/storage/media/{$mediaId}/confirm")
            ->assertOk()
            ->json('data');

        $this->assertSame('confirmed', $media['status']);
        $this->assertSame('private', $media['visibility']);

        $unsignedResponse = Http::get($this->unsignedObjectUrl($media['bucket'], $media['object_key']));
        $this->assertContains($unsignedResponse->status(), [403, 404]);

        $downloadData = $this->getJson("/api/v1/storage/media/{$mediaId}/download-url")
            ->assertOk()
            ->json('data');

        $downloadResponse = Http::get($downloadData['url']);
        $this->assertSame(200, $downloadResponse->status());
        $this->assertSame($binary, $downloadResponse->body());

        $discarded = $this->deleteJson("/api/v1/storage/media/{$mediaId}")
            ->assertOk()
            ->json('data');

        $this->assertSame('discarded', $discarded['status']);
        $this->assertFalse(app(S3CompatibleStorageService::class)->objectExists($media['disk_name'], $media['object_key']));
    }

    public function test_expired_signed_urls_are_rejected(): void
    {
        $user = $this->createVerifiedUser();
        Sanctum::actingAs($user, [], 'sanctum');

        Config::set('filesystems.s3_compatible.upload_url_ttl_minutes', -1);

        $expiredUpload = $this->postJson('/api/v1/storage/upload-slots', [
            'purpose' => 'beauty_input',
            'asset_type' => 'consultation_input_image',
            'owner_type' => 'user',
            'owner_id' => $user->id,
            'file_name' => 'expired-upload.png',
            'content_type' => 'image/png',
            'size_bytes' => 12,
        ])->assertCreated()->json('data');

        $uploadResponse = Http::withHeaders($expiredUpload['headers'])
            ->withBody('expired-body', 'image/png')
            ->send($expiredUpload['method'], $expiredUpload['upload_url']);

        $this->assertContains($uploadResponse->status(), [400, 403]);

        Config::set('filesystems.s3_compatible.upload_url_ttl_minutes', 1);

        $freshSlot = $this->postJson('/api/v1/storage/upload-slots', [
            'purpose' => 'beauty_input',
            'asset_type' => 'consultation_input_image',
            'owner_type' => 'user',
            'owner_id' => $user->id,
            'file_name' => 'confirmed.png',
            'content_type' => 'image/png',
            'size_bytes' => 8,
        ])->assertCreated()->json('data');

        Http::withHeaders($freshSlot['headers'])
            ->withBody('ok-body', 'image/png')
            ->send($freshSlot['method'], $freshSlot['upload_url']);

        $this->postJson("/api/v1/storage/media/{$freshSlot['media_id']}/confirm")->assertOk();

        Config::set('filesystems.s3_compatible.download_url_ttl_minutes', -1);

        $expiredDownload = $this->getJson("/api/v1/storage/media/{$freshSlot['media_id']}/download-url")
            ->assertOk()
            ->json('data');

        $downloadResponse = Http::get($expiredDownload['url']);
        $this->assertContains($downloadResponse->status(), [400, 403]);
    }

    protected function createVerifiedUser(): User
    {
        $user = User::query()->create([
            'name' => 'Storage Tester',
            'email' => 'storage-' . uniqid() . '@example.test',
            'password' => bcrypt('password'),
        ]);

        $user->forceFill(['email_verified_at' => now()])->save();

        return $user;
    }

    protected function unsignedObjectUrl(string $bucket, string $objectKey): string
    {
        $endpoint = rtrim((string) config('filesystems.disks.s3_beauty_inputs.endpoint'), '/');

        return $endpoint . '/' . $bucket . '/' . ltrim($objectKey, '/');
    }
}
