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
        Schema::table('defined_contribution_pensions', function(Blueprint $table) {
            $table->renameColumn('frequency', 'employer_contribution_frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('defined_contribution_pensions', function(Blueprint $table) {
            $table->renameColumn('employer_contribution_frequency', 'frequency');
        });
    }
};
