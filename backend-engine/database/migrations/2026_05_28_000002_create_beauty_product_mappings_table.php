<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_product_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique();
            $table->json('concern_tags')->nullable();
            $table->json('skin_type_tags')->nullable();
            $table->json('tone_tags')->nullable();
            $table->json('undertone_tags')->nullable();
            $table->json('ingredient_tags')->nullable();
            $table->json('avoid_tags')->nullable();
            $table->text('explanation_template')->nullable();
            $table->timestamps();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_product_mappings');
    }
};
