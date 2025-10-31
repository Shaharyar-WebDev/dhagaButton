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
        Schema::create('dyeing_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dyeing_plan_id')->constrained('dyeing_plans');
            $table->foreignId('shade_id')->constrained();
            $table->decimal('quantity', 12, 3);
            // $table->foreignId('unit_id')->constrained();
            $table->date('date');

            // 🧪 QC columns
            $table->enum('qc_status', ['qc_pending', 'rework', 'approved'])->default('qc_pending');
            $table->date('qc_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyeing_plan_items');
    }
};
