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
        Schema::create('lump_sum_capitals', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->integer('amount')->nullable();
            $table->boolean('is_retained')->nullable();
            $table->integer('retained_value')->nullable();
            $table->date('due_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lump_sum_capitals');
    }
};