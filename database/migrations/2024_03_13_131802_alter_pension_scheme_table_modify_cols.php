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
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->renameColumn('lqa_submitted', 'loa_submitted');
            $table->renameColumn('policy_reviewed_transfer', 'is_retained');
            $table->boolean('active_pension_member')->after('is_retained')->nullable();
            $table->text('active_pension_member_reason_not')->after('active_pension_member')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pension_schemes', function (Blueprint $table) {
            $table->renameColumn('loa_submitted', 'lqa_submitted');
            $table->renameColumn('is_retained', 'policy_reviewed_transfer');
            $table->dropColumn('active_pension_member');
            $table->dropColumn('active_pension_member_reason_not');
        });
    }
};
