<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('from', 55)->nullable();
            $table->string('to', 55)->nullable();
            $table->tinyInteger('off')->default(0)->comment('0 => open, 1 => off');
            $table->tinyInteger('day')->nullable()->comment('1 = Friday, 2 = Saturday, 3 = Sunday, 4 = Monday, 5 = Tuesday, 6 = Wednesday, 7 = Thursday');
            $table->integer('branch_id')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->tinyInteger('is_activate')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days');
    }
};
