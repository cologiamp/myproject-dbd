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
        Schema::create('strategy_recommendation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('strategy_report_recommendation_id')->constrained()->index('srr_id_foreign');;
            $table->integer('call_to_action')->nullable();
            $table->string('call_to_action_custom')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_recommendation_actions');
    }
};
