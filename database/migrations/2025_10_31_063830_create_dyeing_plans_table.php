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
        Schema::create('dyeing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_number');
            $table->foreignId('dyer_id')->constrained('suppliers', 'id');
            $table->enum('qc_status', ['qc_pending', 'rework', 'approved'])->default('qc_pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyeing_plans');
    }
};
