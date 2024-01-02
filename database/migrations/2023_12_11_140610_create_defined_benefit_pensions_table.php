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
        Schema::create('defined_benefit_pensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pension_scheme_id');
            $table->integer('status');
            $table->integer('prospective_pension_standard')->nullable();
            $table->integer('prospective_pension_max')->nullable();
            $table->integer('prospective_pcls_standard')->nullable();
            $table->integer('prospective_pcls_max')->nullable();
            $table->integer('cetv')->nullable();
            $table->date('cetv_ends_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defined_benefit_pensions');
    }
};
