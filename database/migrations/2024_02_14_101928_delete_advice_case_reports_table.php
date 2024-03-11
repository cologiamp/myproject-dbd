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
        Schema::dropIfExists('advice_case_report');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('advice_case_report', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained();
            $table->foreignId('advice_case_id')->constrained();
            $table->foreignId('client_id')->nullable();
            $table->boolean('is_joint')->default(false);
            $table->timestamps();
        });
    }
};
