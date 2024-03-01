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
        Schema::table('pension_recommendations', function (Blueprint $table) {
            $table->integer('employment_status')->after('report_type')->nullable();
            $table->string('current_employer_name')->after('employment_status')->nullable();
            $table->integer('workplace_pension_type')->after('current_employer_name')->nullable();
            $table->string('employers_pension_name')->after('workplace_pension_type')->nullable();
            $table->string('active_pension_review_transfer_reason')->after('employers_pension_name')->nullable();
            $table->integer('pension_draw_age')->after('active_pension_review_transfer_reason')->nullable();
            $table->integer('retirement_option')->after('pension_draw_age')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pension_recommendations', function (Blueprint $table) {
            $table->dropColumn('employment_status');
            $table->dropColumn('current_employer_name');
            $table->dropColumn('workplace_pension_type');
            $table->dropColumn('employers_pension_name');
            $table->dropColumn('active_pension_review_transfer_reason');
            $table->dropColumn('pension_draw_age');
            $table->dropColumn('retirement_option');
        });
    }
};
