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
        Schema::create('capacity_for_losses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->integer('investment_length')->nullable();
            $table->integer('standard_of_living')->nullable();
            $table->integer('emergency_funds')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacity_for_losses');
    }
};
