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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('raw_material_id')->constrained();
            $table->decimal('ordered_quantity', 12, 3);
            $table->decimal('rate', 12, 2)->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->enum('status', ['draft', 'pending', 'partially_received', 'completed', 'cancelled'])->default('pending');
            $table->text('remarks')->nullable();
            $table->date('expected_delivery_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
