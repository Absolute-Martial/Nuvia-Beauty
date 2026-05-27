<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_recommendations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('profile_id')->nullable()->index();
            $table->string('session_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedTinyInteger('score');
            $table->string('confidence', 32);
            $table->json('reasons_json');
            $table->json('warnings_json');
            $table->json('breakdown_json');
            $table->string('score_version', 64);
            $table->boolean('accepted')->nullable();
            $table->boolean('dismissed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_recommendations');
    }
};
