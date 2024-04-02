<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->integer('io_id')->nullable()->after('type');
            $table->integer('plan_io_id')->nullable()->after('type');
            $table->integer('contribution_io_id')->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('io_id');
            $table->dropColumn('plan_io_id');
            $table->dropColumn('contribution_io_id');
        });
    }
};
