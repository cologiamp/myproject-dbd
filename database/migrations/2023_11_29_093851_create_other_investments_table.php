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
        Schema::create('other_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->integer('provider')->nullable();
            $table->integer('contract_type')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('current_value')->nullable();
            $table->date('valuation_at')->nullable();
            $table->integer('retained_value')->nullable();
            $table->integer('regular_contribution')->nullable();
            $table->integer('frequency')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('maturity_date')->nullable();
            $table->boolean('is_retained')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_investments');
    }
};
