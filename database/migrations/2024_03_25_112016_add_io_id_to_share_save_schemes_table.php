<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('share_save_schemes', function (Blueprint $table) {
            $table->integer('io_id')->nullable();
            $table->integer('plan_io_id')->nullable();
            $table->integer('contribution_io_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('share_save_schemes', function (Blueprint $table) {
            $table->dropColumn('io_id');
            $table->dropColumn('plan_io_id');
            $table->dropColumn('contribution_io_id');
        });
    }
};
