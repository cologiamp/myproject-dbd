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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->integer('io_id');
            $table->integer('case_id');
            $table->integer('title')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->boolean('is_primary')->nullable();
            $table->integer('relation_to_c2')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('salutation')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('marital_status')->nullable();
            $table->integer('nationality')->nullable();
            $table->string('ni_number')->nullable();
            $table->integer('country_of_domicile')->nullable();
            $table->integer('country_of_residence')->nullable();
            $table->boolean('valid_will')->nullable();
            $table->boolean('will_up_to_date')->nullable();
            $table->boolean('poa_granted')->nullable();
            $table->boolean('assets_not_declared')->nullable();
            $table->boolean('liabilities_not_declared')->nullable();
            $table->boolean('other_investments_not_disclosed')->nullable();
            $table->boolean('other_accounts_not_disclosed')->nullable();
            $table->boolean('pension_schemes_not_disclosed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
