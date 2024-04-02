<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('client_dependent', function (Blueprint $table) {
            $table->string('io_id', 125)->nullable()->after('client_id');
        });
    }

    public function down(): void
    {
        Schema::table('client_dependent', function (Blueprint $table) {
            $table->dropColumn('io_id');
        });
    }
};
