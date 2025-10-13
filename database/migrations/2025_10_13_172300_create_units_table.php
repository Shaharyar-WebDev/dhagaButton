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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // e.g. Kilogram
            $table->string('symbol', 50)->nullable(); // e.g. kg
            $table->enum('conversion_operator', ['*', '/'])->nullable(); // for converting to base unit
            $table->decimal('conversion_value', 8, 2)->nullable(); // value to multiply/divide by
            $table->foreignId('base_unit_id')->nullable()->constrained('units')->nullOnDelete(); // self relation
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
