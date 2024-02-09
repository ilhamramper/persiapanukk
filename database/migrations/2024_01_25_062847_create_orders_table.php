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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger('id_user');
            $table->integer('no_meja');
            $table->unsignedBigInteger('status_order')->default(1);
            $table->timestamps();

            $table->foreign('no_meja')->references('no_meja')->on('nomejas');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('status_order')->references('id')->on('status_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
