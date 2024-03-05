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
        Schema::create('strategy_report_recommendations', function (Blueprint $table) {
            $table->id();
            $table->integer('report_version')->nullable();
            $table->integer('retirement_status')->nullable();
            $table->integer('objective_type')->nullable();
            $table->date('next_meeting_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategy_report_recommendations');
    }
};
