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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 1255)->nullable();
            $table->string('slug', 1255)->nullable();
            $table->text('description', 12550)->nullable();
            $table->string('imgs', 1255)->nullable();
            $table->integer('category_id')->nullable();
            $table->string('meta_title', 1255)->nullable();
            $table->string('meta_description', 1255)->nullable();
            $table->string('meta_tags', 1255)->nullable();
            $table->string('meta_keywords', 1255)->nullable();
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
        Schema::dropIfExists('blogs');
    }
};
