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
        Schema::create('supplier_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');

            // Links to related document
            $table->morphs('source'); // e.g. source_type='GoodsReceivedNote', source_id=12

            // Transaction type
            $table->string('transaction_type')->nullable(); // e.g. PO, GRN, STR, DYE_JOB, PAYMENT

            // Amount columns
            $table->decimal('debit', 14, 3)->default(0);   // Supplier owes factory
            $table->decimal('credit', 14, 3)->default(0);  // Factory owes supplier
            $table->decimal('balance', 14, 3)->default(0); // Running balance (credit - debit)

            // Optional reference info
            $table->string('reference_no')->nullable();    // GRN no, DO no, Bill no, etc.
            $table->date('date')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_ledgers');
    }
};
