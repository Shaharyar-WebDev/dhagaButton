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
        Schema::create('twister_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('twister_id')->constrained('suppliers', 'id');
            $table->foreignId('delivery_order_id')->nullable()->constrained('delivery_orders')->cascadeOnDelete();
            $table->foreignId('dyer_id')->nullable()->constrained('suppliers', 'id');
            $table->foreignId('goods_received_note_id')->nullable()->constrained('goods_received_notes')->cascadeOnDelete();
            $table->foreignId('stock_transfer_record_id')->nullable()->constrained('stock_transfer_records')->cascadeOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained();
            $table->foreignId('raw_material_id')->constrained();
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            // $table->decimal('debit', 14, 2)->default(0);
            // $table->decimal('credit', 14, 2)->default(0);
            $table->decimal('issue', 14, 2)->default(0);
            $table->decimal('receive', 14, 2)->default(0);
            $table->decimal('balance', 14, 2)->default(0);
            $table->dateTime('date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twister_inventories');
    }
};
