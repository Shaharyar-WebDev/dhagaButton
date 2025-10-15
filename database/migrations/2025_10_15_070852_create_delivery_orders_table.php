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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('do_number');
            $table->string('delivery_order_reference');
            $table->json('delivery_order_images')->nullable();
            $table->string('challan_reference');
            $table->foreignId('purchase_order_id')->constrained();
            $table->foreignId('raw_material_id')->constrained('raw_materials'); // Yarn
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('twister_id')->constrained('suppliers', 'id');
            $table->decimal('quantity', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
