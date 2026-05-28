<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('user_profile_id')->nullable()->constrained('user_profiles')->nullOnDelete();
            $table->string('source', 32)->index();
            $table->string('customer_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->json('skin_type_tags')->nullable();
            $table->json('tone_tags')->nullable();
            $table->json('undertone_tags')->nullable();
            $table->json('concern_tags')->nullable();
            $table->json('ingredient_tags')->nullable();
            $table->json('avoid_tags')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['shop_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_profiles');
    }
};
