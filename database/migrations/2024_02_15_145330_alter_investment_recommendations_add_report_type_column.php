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
            $table->integer('report_type')->nullable()
                ->after('risk_profile')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_recommendations', function (Blueprint $table) {
            $table->dropColumn(['report_type']);
        });
    }
};
