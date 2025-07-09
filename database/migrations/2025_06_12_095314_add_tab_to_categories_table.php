<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function ($table) {
            $table->boolean('tab')->default(0)->after('img');
        });
    }

    public function down()
    {
        Schema::table('categories', function ($table) {
            $table->dropColumn('tab');
        });
    }
};
