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
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->integer('crystallised_status')->nullable();
            $table->integer('crystallised_percentage')->nullable();
            $table->string('fund_name')->nullable();
            $table->integer('fund_type')->nullable();
            $table->integer('current_fund_value')->nullable();
            $table->integer('current_transfer_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->dropColumn(['crystallised_status','crystallised_percentage','fund_name','fund_type','current_fund_value','current_transfer_value']);
        });
    }
};
