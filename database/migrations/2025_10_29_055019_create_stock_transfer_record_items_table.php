<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_transfer_record_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_record_id')->constrained('stock_transfer_records')->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_record_items');
    }
};
