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
        Schema::table('other_investments', function (Blueprint $table) {
            $table->integer('lump_sum_contribution')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_investments', function (Blueprint $table) {
            $table->dropColumn(['lump_sum_contribution']);
        });
    }
};
