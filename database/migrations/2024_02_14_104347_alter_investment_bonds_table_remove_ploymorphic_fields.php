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
            $table->dropColumn('investmentbondable_id');
            $table->dropColumn('investmentbondable_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_bonds', function (Blueprint $table) {
            $table->integer('investmentbondable_id')->after('initial_investment');
            $table->string('investmentbondable_type')->after('investmentrecommendationable_id');
        });
    }
};
