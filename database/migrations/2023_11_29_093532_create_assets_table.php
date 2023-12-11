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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->integer('category')->nullable();
            $table->integer('type');//0 = Asset 1 = existing account
            $table->text('description')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('original_value')->nullable();
            $table->integer('current_value')->nullable();
            $table->integer('retained_value')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('is_retained')->nullable();
            $table->double('interest_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
