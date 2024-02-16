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
        Schema::table('assets', function (Blueprint $table) {
            $table->string('provider')->nullable()->change();
        });
        Schema::table('other_investments', function (Blueprint $table) {
            $table->string('provider')->nullable()->change();
        });
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->string('administrator')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->integer('provider')->nullable()->change();
        });
        Schema::table('other_investments', function (Blueprint $table) {
            $table->integer('provider')->nullable()->change();
        });
        Schema::table('defined_contribution_pensions', function (Blueprint $table) {
            $table->integer('administrator')->nullable()->change();
        });
    }
};
