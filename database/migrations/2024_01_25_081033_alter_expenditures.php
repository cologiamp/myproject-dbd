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
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropConstrainedForeignId('advice_case_id');
            $table->string('ends_at')->nullable()->after('type');
            $table->string('starts_at')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn(['description','starts_at','ends_at']);
            $table->foreignId('advice_case_id')->constrained();
        });
    }
};
