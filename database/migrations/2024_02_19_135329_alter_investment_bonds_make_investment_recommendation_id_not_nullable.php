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
        Schema::table('investment_bonds', function (Blueprint $table) {
            $table->foreignId('investment_recommendation_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_bonds', function (Blueprint $table) {
            $table->foreignId('investment_recommendation_id')->nullable();
        });
    }
};
