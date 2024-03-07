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
        Schema::create('pension_funds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('defined_contribution_pension_id');
            $table->string('fund_name')->nullable();
            $table->integer('fund_type')->nullable();
            $table->integer('current_fund_value')->nullable();
            $table->integer('current_transfer_value')->nullable();
            $table->timestamps();
        });
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->dropColumn(['fund_name','fund_type','current_fund_value','current_transfer_value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pension_funds');
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->string('fund_name')->nullable();
            $table->integer('fund_type')->nullable();
            $table->integer('current_fund_value')->nullable();
            $table->integer('current_transfer_value')->nullable();
        });
    }
};
