<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jmasakans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_masakan');
            $table->timestamps();
        });

        DB::table('jmasakans')->insert([
            ['jenis_masakan' => 'Makanan'],
            ['jenis_masakan' => 'Minuman'],
            ['jenis_masakan' => 'Camilan'],
            ['jenis_masakan' => 'Buah'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jmasakans');
    }
};
