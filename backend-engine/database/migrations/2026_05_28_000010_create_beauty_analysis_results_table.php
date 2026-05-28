<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_analysis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beauty_session_id')->constrained('beauty_sessions')->cascadeOnDelete();
            $table->foreignId('beauty_ai_task_id')->nullable()->constrained('beauty_ai_tasks')->nullOnDelete();
            $table->string('provider', 64);
            $table->string('status', 32)->index();
            $table->json('summary_payload')->nullable();
            $table->json('normalized_traits')->nullable();
            $table->unsignedInteger('recommendation_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_analysis_results');
    }
};
