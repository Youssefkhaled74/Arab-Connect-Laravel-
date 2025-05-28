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
        Schema::table('users', function (Blueprint $table) {
            $table->string('country_id', 10)->nullable()->after('country_flag');
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->string('country_id', 10)->nullable()->after('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_and_branch_tables', function (Blueprint $table) {
            //
        });
    }
};
