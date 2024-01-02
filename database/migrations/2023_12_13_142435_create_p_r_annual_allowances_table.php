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
        Schema::create('p_r_annual_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_recommendation_id')->constrained();
            $table->string('tax_year');
            $table->integer('annual_allowance');
            $table->integer('pension_input')->default(0);
            $table->integer('unused_allowance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_r_annual_allowances');
    }
};
