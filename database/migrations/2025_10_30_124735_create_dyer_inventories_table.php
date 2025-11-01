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
        Schema::create('dyer_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_supplier_id')->nullable()->constrained('suppliers', 'id');
            $table->foreignId('dyer_id')->constrained('suppliers', 'id');
            // $table->foreignId('goods_received_note_id')->nullable()->constrained('goods_received_notes')->cascadeOnDelete();
            $table->foreignId('stock_transfer_record_id')->nullable()->constrained('stock_transfer_records')->cascadeOnDelete();
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
        Schema::dropIfExists('dyer_inventories');
    }
};
