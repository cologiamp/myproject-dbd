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
        Schema::create('pension_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->integer('previously_invested_amount')->nullable();
            $table->integer('fee_basis')->nullable();
            $table->text('fee_basis_discount')->nullable();
            $table->integer('report_type')->nullable();
            $table->boolean('active_pension_member')->nullable();
            $table->text('active_pension_member_reason_not')->nullable();
            $table->integer('active_pension_review_for_transfer')->nullable();
            $table->integer('dd_pcls_spend')->nullable();
            $table->integer('dd_pcls_income')->nullable();
            $table->integer('dd_income')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pension_recommendations');
    }
};
