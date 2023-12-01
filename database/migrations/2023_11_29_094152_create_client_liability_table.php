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
        Schema::create('client_liability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liability_id')->constrained();
            $table->foreignId('client_id')->constrained();
//            $table->integer('percent_ownership')->default(100); //TBC based on IO
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_liability');
    }
};
