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
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->integer('lqa_submitted')->after('retirement_age')->nullable();
            $table->integer('policy_reviewed_transfer')->after('lqa_submitted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->dropColumn('lqa_submitted');
            $table->dropColumn('policy_reviewed_transfer');
        });
    }
};
