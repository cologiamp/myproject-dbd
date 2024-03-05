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
        Schema::table('investment_recommendations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('advice_case_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_recommendations', function (Blueprint $table) {
            $table->foreignId('advice_case_id')->constrained();
        });
    }
};