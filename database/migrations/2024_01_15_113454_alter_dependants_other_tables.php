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
        Schema::rename('client_dependent','client_dependant');
        Schema::table('retirements', function (Blueprint $table) {
            $table->renameColumn('dependents_suffer','dependants_suffer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('client_dependant','client_dependent');
        Schema::table('retirements', function (Blueprint $table) {
            $table->renameColumn('dependants_suffer','dependents_suffer');
        });
    }
};
