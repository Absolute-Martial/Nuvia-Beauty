<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beauty_quota_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->string('provider', 64);
            $table->string('feature_key', 64);
            $table->string('plan_code', 64)->nullable();
            $table->unsignedInteger('allocated_units')->nullable();
            $table->unsignedInteger('used_units')->default(0);
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();

            $table->unique(['shop_id', 'provider', 'feature_key'], 'beauty_quota_accounts_shop_provider_feature_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_quota_accounts');
    }
};
