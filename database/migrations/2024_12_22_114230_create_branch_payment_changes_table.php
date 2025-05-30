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
        Schema::create('branch_payment_changes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('branch_id')->nullable();
            $table->integer('payment_method_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_payment_changes');
    }
};
