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
        Schema::table('p_r_annual_allowances', function (Blueprint $table) {
            $table->integer('pension_input')->nullable()->change();
            $table->integer('unused_allowance')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('p_r_annual_allowances', function (Blueprint $table) {
            $table->integer('pension_input')->nullable(false)->change();
            $table->integer('unused_allowance')->nullable(false)->change();
        });
    }
};
