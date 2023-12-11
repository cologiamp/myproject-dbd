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
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->boolean('is_repayment')->nullable();
            $table->integer('amount_outstanding')->nullable();
            $table->integer('monthly_repayment')->nullable();
            $table->string('lender')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('is_to_be_repaid')->nullable();
            $table->text('repay_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liabilities');
    }
};
