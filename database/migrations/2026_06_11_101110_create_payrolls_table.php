<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('month');                    // Format: 2026-06
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2);
            
            // Tanzania Statutory Deductions
            $table->decimal('nssf_employee', 15, 2)->default(0);
            $table->decimal('nhif', 15, 2)->default(0);
            $table->decimal('paye', 15, 2)->default(0);
            $table->decimal('other_deductions', 15, 2)->default(0);
            
            $table->decimal('net_salary', 15, 2);
            $table->enum('status', ['draft', 'processed', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'month']); // One payroll per employee per month
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
