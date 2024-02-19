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
        Schema::table('retirements', function (Blueprint $table) {
            $table->integer('lump_sum_death_benefits')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retirements', function (Blueprint $table) {
            $table->dropColumn('lump_sum_death_benefits');
        });
    }
};
