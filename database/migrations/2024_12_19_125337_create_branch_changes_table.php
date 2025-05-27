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
        Schema::create('branch_changes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('branch_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('mobile', 255)->nullable();
            $table->string('location', 1550)->nullable();
            $table->string('map_location', 1550)->nullable();
            $table->string('lat', 55)->nullable();
            $table->string('lon', 55)->nullable();
            $table->string('face', 1550)->nullable();
            $table->string('insta', 1550)->nullable();
            $table->string('tiktok', 1550)->nullable();
            $table->string('website', 1550)->nullable();
            $table->string('imgs', 1550)->nullable();
            $table->string('tax_card', 255)->nullable();
            $table->string('commercial_register', 255)->nullable();
            $table->boolean('is_activate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edit_requests');
    }
};
