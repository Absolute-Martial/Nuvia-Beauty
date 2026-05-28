<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beauty_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('user_profiles')->nullOnDelete();
            $table->string('session_id', 100)->nullable()->index();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->string('event_type', 30)->index();
            $table->string('source_surface', 30)->nullable();
            $table->foreignId('recommendation_id')->nullable()->constrained('beauty_recommendations')->nullOnDelete();
            $table->json('metadata_json')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_events');
    }
};
