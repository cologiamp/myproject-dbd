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
        Schema::create('knowledge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->text('particular_issues')->nullable();
            $table->integer('level_of_knowledge')->nullable();
            $table->boolean('aware_of_market_fluctuations')->nullable();
            $table->integer('comfort_of_fluctuations')->nullable();
            $table->boolean('fluctuation_comfort')->nullable();
            $table->boolean('active_interest')->nullable();
            $table->boolean('discretionary_experience')->nullable();
            $table->boolean('execution_only_experience')->nullable();
            $table->boolean('ever_taken_invest_advice')->nullable();

            $table->boolean('experience_of_annuities')->nullable();
            $table->boolean('experience_of_income_drawdown')->nullable();
            $table->boolean('experience_of_phased_retirement')->nullable();
            $table->boolean('spoken_to_pensionwise')->nullable();

            $table->json('experience_buying_cash')->nullable();
            $table->json('experience_buying_bonds')->nullable();
            $table->json('experience_buying_equities')->nullable();
            $table->json('experience_buying_insurance')->nullable();
            $table->text('experience_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge');
    }
};
