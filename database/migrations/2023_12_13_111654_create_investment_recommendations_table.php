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
        Schema::create('investment_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advice_case_id')->constrained();
            $table->boolean('is_ethical_investor')->nullable();
            $table->integer('previously_invested_amount')->nullable();
            $table->integer('fee_basis')->nullable();
            $table->text('fee_basis_discount')->nullable();


            //NOTE: Unsure if this needs to be separated for client 1 and client 2. Move anything to client_recommendations table if needed
            $table->integer('net_income_required')->nullable();
            $table->integer('regular_cash_required')->nullable();
            $table->integer('regular_cash_duration')->nullable();

            $table->text('cta_base_costs_available')->nullable();
            $table->boolean('cta_sell_to_cgt_exemption')->default(false);
            $table->boolean('cta_sell_all')->nullable()->default(false);
            $table->string('cta_sell_set_amount')->nullable();

            $table->text('dta_base_costs_available')->nullable();
            $table->boolean('dta_sell_to_cgt_exemption')->default(false);
            $table->boolean('dta_sell_all')->nullable()->default(false);
            $table->string('dta_sell_set_amount')->nullable();

            $table->boolean('isa_transfer_exit_penalty_not_ascertained')->default(false);
            $table->text('isa_transfer_exit_penalty_ascertained');

            $table->boolean('investment_bonds_managed_funds')->default(false);
            $table->boolean('investment_bonds_with_profits')->default(false);
            $table->boolean('investment_bonds_chargeable_gain_not_calculated')->default(false);
            $table->boolean('investment_bonds_exit_penalty_not_ascertained')->default(false);
            $table->text('investment_bonds_exit_penalty_ascertained');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
