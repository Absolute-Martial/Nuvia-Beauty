<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 64)->unique();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('consultant_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_profile_id')->nullable()->constrained('user_profiles')->nullOnDelete();
            $table->foreignId('beauty_profile_id')->nullable()->constrained('beauty_profiles')->nullOnDelete();
            $table->unsignedBigInteger('current_snapshot_id')->nullable()->index();
            $table->foreignId('primary_media_asset_id')->nullable()->constrained('beauty_media_assets')->nullOnDelete();
            $table->string('consultation_mode', 32);
            $table->string('session_state', 32)->index();
            $table->text('notes')->nullable();
            $table->timestamp('saved_at')->nullable();
            $table->timestamp('discarded_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('beauty_profile_snapshots', function (Blueprint $table) {
            $table->foreign('beauty_session_id')
                ->references('id')
                ->on('beauty_sessions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('beauty_profile_snapshots', function (Blueprint $table) {
            $table->dropForeign(['beauty_session_id']);
        });

        Schema::dropIfExists('beauty_sessions');
    }
};
