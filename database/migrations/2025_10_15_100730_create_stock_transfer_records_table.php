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
        Schema::create('stock_transfer_records', function (Blueprint $table) {
            $table->id();
            $table->string('str_number')->unique();
            $table->foreignId('raw_material_id')->constrained('raw_materials');
            $table->foreignId('from_supplier_id')->nullable()->constrained('suppliers', 'id')->nullOnDelete();
            $table->foreignId('to_supplier_id')->nullable()->constrained('suppliers', 'id')->nullOnDelete();
            $table->string('challan_no')->nullable();
            $table->dateTime('challan_date');
            $table->enum('status', ['draft', 'verified']);
            $table->boolean('locked')->default(false);
            $table->json('attachments')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_records');
    }
};
