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
        Schema::create('masakans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jmasakan');
            $table->string('nama_masakan');
            $table->integer('harga');
            $table->string('status_masakan')->default('Tidak Tersedia');
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('id_jmasakan')->references('id')->on('jmasakans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masakans');
    }
};
