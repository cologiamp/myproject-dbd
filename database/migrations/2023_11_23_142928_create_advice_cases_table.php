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
        Schema::create('advice_cases', function (Blueprint $table) {
            $table->id();
            $table->integer('advisor_id');
            $table->dateTime('meeting_one_date')->nullable();
            $table->integer('meeting_one_type')->nullable();
            $table->text('anyone_else_present')->nullable();
            $table->boolean('client_two_exists')->nullable();
            $table->boolean('client_two_present')->nullable();
            $table->dateTime('meeting_two_date')->nullable();
            $table->text('origin_of_wealth')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advice_cases');
    }
};
