<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //defined_contribution_pensions
    public function up(): void
    {
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->integer('retained_value')->nullable();
            $table->boolean('is_retained')->nullable();
        });
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->dropColumn(['is_retained']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->dropColumn(['retained_value','is_retained']);
        });
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->boolean('is_retained')->nullable();
        });
    }

};
