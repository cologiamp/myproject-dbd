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
        Schema::table('client_investment_recommendation_items', function (Blueprint $table) {
//            $table->foreignId('client_id')->after('id')->references('id')->on('clients');
//            $table->foreignId('investment_recommendation_item_id')->after('client_id')->references('id')->on('investment_recommendation_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_investment_recommendation_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
            $table->dropConstrainedForeignId('investment_recommendation_item_id');
        });
    }
};
