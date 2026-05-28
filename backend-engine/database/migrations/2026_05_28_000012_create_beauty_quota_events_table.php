<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_quota_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beauty_quota_account_id')->constrained('beauty_quota_accounts')->cascadeOnDelete();
            $table->foreignId('beauty_session_id')->nullable()->constrained('beauty_sessions')->nullOnDelete();
            $table->string('event_type', 64)->index();
            $table->integer('delta_units')->default(0);
            $table->json('metadata_json')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_quota_events');
    }
};
