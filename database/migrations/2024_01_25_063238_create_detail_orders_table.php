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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_masakan');
            $table->integer('qty');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('status_detail_order')->default(2);
            $table->string('alasan')->nullable();
            $table->timestamps();
            $table->foreign('id_order')->references('id')->on('orders');
            $table->foreign('id_masakan')->references('id')->on('masakans');
            $table->foreign('status_detail_order')->references('id')->on('status_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
