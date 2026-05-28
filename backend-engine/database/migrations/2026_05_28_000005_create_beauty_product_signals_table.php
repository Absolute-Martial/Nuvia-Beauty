<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('beauty_product_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('add_to_cart_count')->default(0);
            $table->unsignedBigInteger('purchase_count')->default(0);
            $table->decimal('weighted_score', 10, 2)->default(0);
            $table->string('signal_version', 50)->default('phase4_v1');
            $table->timestamp('last_event_at')->nullable();
            $table->timestamp('last_recomputed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beauty_product_signals');
    }
};
