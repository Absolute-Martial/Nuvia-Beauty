<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable()->index();
            $table->unsignedBigInteger('profile_id')->nullable()->index();
            $table->unsignedBigInteger('shop_id')->nullable()->index();
            $table->string('owner_type');
            $table->unsignedBigInteger('owner_id');
            $table->string('asset_type');
            $table->string('storage_provider');
            $table->string('disk_name');
            $table->string('bucket');
            $table->string('object_key');
            $table->string('object_version')->nullable();
            $table->string('content_type');
            $table->unsignedBigInteger('size_bytes');
            $table->string('checksum_sha256', 64)->nullable();
            $table->string('visibility', 32);
            $table->string('status', 32)->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('discarded_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
            $table->index(['disk_name', 'bucket']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_media_assets');
    }
};
