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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('mobile', 255)->nullable();
            $table->string('location', 1550)->nullable();
            $table->string('map_location', 1550)->nullable();
            $table->string('imgs', 1550)->nullable();
            $table->string('tax_card', 255)->nullable();
            $table->string('commercial_register', 255)->nullable();
            $table->string('face', 1550)->nullable();
            $table->string('insta', 1550)->nullable();
            $table->string('tiktok', 1550)->nullable();
            $table->string('website', 1550)->nullable();
            $table->integer('category_id')->nullable();
            $table->string('uuid', 50)->nullable();
            $table->integer('owner_id')->nullable();
            $table->string('lat', 55)->nullable();
            $table->string('lon', 55)->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->tinyInteger('is_activate')->default(1);
            $table->tinyInteger('is_published')->default(0);
            $table->tinyInteger('is_verified')->default(0);
            $table->timestamp('expire_at')->nullable();
            $table->timestamp('three_month_email_sent_at')->nullable();
            $table->timestamp('one_month_email_sent_at')->nullable();
            $table->timestamp('expired_email_sent_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
};
