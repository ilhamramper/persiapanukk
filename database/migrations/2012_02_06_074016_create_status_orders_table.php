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
        Schema::create('status_orders', function (Blueprint $table) {
            $table->id();
            $table->string('status_order');
            $table->timestamps();
        });

        DB::table('status_orders')->insert([
            ['status_order' => 'Belum Membuat Pesanan'],
            ['status_order' => 'Belum Menyimpan Pesanan'],
            ['status_order' => 'Belum Dibayar'],
            ['status_order' => 'Sudah Dibayar'],
            ['status_order' => 'Diproses'],
            ['status_order' => 'Selesai'],
            ['status_order' => 'Dibatalkan'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_orders');
    }
};
