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
        Schema::create('risk_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->integer('attitude_to_risk')->nullable();
            $table->integer('capacity_for_loss_investment')->nullable();
            $table->integer('capacity_for_loss_pension')->nullable();
            $table->integer('knowledge_and_experience_investment')->nullable();
            $table->integer('knowledge_and_experience_pension')->nullable();
            $table->integer('assessment_result_investment')->nullable();
            $table->integer('assessment_result_pension')->nullable();
            $table->boolean('using_calculated_risk_profile_investment')->nullable();
            $table->boolean('using_calculated_risk_profile_pension')->nullable();
            $table->integer('adviser_recommendation_investment')->nullable();
            $table->integer('adviser_recommendation_pension')->nullable();
            $table->text('why_investment')->nullable();
            $table->text('why_pension')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_outcomes');
    }
};
