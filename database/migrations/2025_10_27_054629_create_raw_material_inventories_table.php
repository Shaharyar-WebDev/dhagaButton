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
        Schema::create('raw_material_inventories', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('raw_material_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->decimal('in_qty', 12, 2)->default(0);
            $table->decimal('out_qty', 12, 2)->default(0);
            $table->decimal('balance', 12, 2);
            $table->decimal('rate', 12, 3)->nullable()->default(null); // Only for IN entries
            $table->decimal('value', 15, 3)->nullable()->default(null); // in_qty * rate
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable()->default(null);
            $table->string('remarks')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_material_inventories');
    }
};
