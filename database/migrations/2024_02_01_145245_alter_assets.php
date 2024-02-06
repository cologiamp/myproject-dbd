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
        Schema::table('assets', function (Blueprint $table) {
               $table->integer('provider')->after('interest_rate')->nullable();
               $table->integer('account_type')->after('interest_rate')->nullable();
        });
    }

        /**
         * Reverse the migrations.
         */
        public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['provider','account_type']);
        });
    }
    };
