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
        Schema::create('risk_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->boolean('comfort_fluctuate_market')->nullable();
            $table->integer('day_to_day_volatility')->nullable();
            $table->json('short_term_volatility')->nullable();
            $table->integer('medium_term_volatility')->nullable();
            $table->integer('volatility_behaviour')->nullable();
            $table->integer('time_horizon')->nullable();
            $table->integer('long_term_volatility')->nullable();
            $table->integer('time_in_market')->nullable();

            $table->text('risk_profile_details')->nullable();
            $table->integer('risk_profile_as_agreed')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_profiles');
    }
};
