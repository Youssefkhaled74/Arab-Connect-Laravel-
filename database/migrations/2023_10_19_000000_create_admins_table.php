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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 60);
            $table->string('email', 60);
            $table->string('phone', 30);
            $table->string('img', 255)->nullable();
            $table->string('password', 60);
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
        Schema::dropIfExists('admins');
    }
};
