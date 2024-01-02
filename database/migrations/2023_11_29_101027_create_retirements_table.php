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
        Schema::create('retirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->date('intended_retirement_at')->nullable();
            $table->date('intended_benefits_drawn_at')->nullable();
            $table->integer('income_option')->nullable();
            $table->json('lifetime_allowance_protection')->nullable();

            $table->boolean('additional_contributions')->nullable();
            $table->boolean('in_specie_transfers')->nullable();

            //ACCUMULATION QUESTIONS
            $table->integer('if_experience_self_select')->nullable();
            $table->integer('if_experience_lifestyle')->nullable();
            $table->integer('if_experience_advisory')->nullable();
            $table->integer('if_experience_discretionary')->nullable();

            $table->boolean('is_explained')->nullable();
            $table->integer('preferred_option')->nullable();
            $table->text('preferred_explanation')->nullable();
            $table->boolean('wide_range_of_assets')->nullable();
            $table->text('include_exclude_specifics')->nullable(); //if null, bool yes/no

            $table->boolean('require_flexibility')->nullable();

            $table->integer('retirement_vs_legacy')->nullable();
            $table->text('retirement_vs_legacy_specifics')->nullable();

            $table->text('dependents_suffer')->nullable();
            $table->boolean('iht_concerns')->nullable();

            //DECUMULATION QUESTIONS
            $table->boolean('known_income_required')->nullable();
            $table->boolean('prefer_flexibility')->nullable();
            $table->integer('what_age_annuity')->nullable();

            $table->integer('proportion_of_total_funds')->nullable();
            $table->integer('spouse_income_proportion')->nullable();
            $table->integer('spouse_lump_sum_death')->nullable();
            $table->boolean('maximise_lifetime')->nullable();
            $table->boolean('no_spouse')->nullable();
            $table->text('spouse_details')->nullable();

            $table->integer('tax_free_lump_sum_preference')->nullable();
            $table->integer('tax_free_lump_sum_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retirements');
    }
};
