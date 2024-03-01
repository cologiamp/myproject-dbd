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
        Schema::dropIfExists('client_investment_recommendation');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('client_investment_recommendation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_recommendation_id')->constrained(indexName: 'cir_ir_id_foriegn');
            $table->foreignId('client_id')->constrained();
            $table->integer('isa_allowance_used')->nullable();
            $table->integer('cgt_allowance_used')->nullable();
            $table->timestamps();
        });
    }
};
