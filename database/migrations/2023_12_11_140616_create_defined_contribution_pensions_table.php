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
        Schema::create('defined_contribution_pensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_scheme_id');
            $table->integer('type')->nullable();
            $table->integer('administrator')->nullable();
            $table->date('policy_start_at')->nullable();
            $table->string('policy_number')->nullable();
            $table->integer('gross_contribution_percent')->nullable();
            $table->integer('gross_contribution_absolute')->nullable();
            $table->integer('employer_contribution_percent')->nullable();
            $table->integer('employer_contribution_absolute')->nullable();
            $table->integer('value')->nullable();
            $table->date('valuation_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defined_contribution_pensions');
    }
};
