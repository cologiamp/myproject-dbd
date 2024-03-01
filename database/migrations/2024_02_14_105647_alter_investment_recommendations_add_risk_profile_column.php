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
            $table->integer('risk_profile')->after('is_ethical_investor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_recommendations', function (Blueprint $table) {
            $table->dropColumn('risk_profile');
        });
    }
};
