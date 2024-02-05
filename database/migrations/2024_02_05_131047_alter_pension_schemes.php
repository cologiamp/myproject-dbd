<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->dropColumn(['pensionable_type','pensionable_id']);
        });
        Schema::table('defined_benefit_pensions', function (Blueprint $table) {
            $table->integer('status')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->integer('pensionable_type');
            $table->integer('pensionable_id');
        });
        Schema::table('defined_benefit_pensions', function (Blueprint $table) {
            $table->integer('status')->nullable(false)->change();
        });
    }
};
