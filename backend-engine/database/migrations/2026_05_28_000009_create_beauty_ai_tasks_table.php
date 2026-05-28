<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_ai_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beauty_session_id')->constrained('beauty_sessions')->cascadeOnDelete();
            $table->string('provider', 64);
            $table->string('task_type', 64);
            $table->string('status', 32)->index();
            $table->string('provider_task_id')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_ai_tasks');
    }
};
