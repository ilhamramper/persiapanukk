<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('nama_user');
            $table->unsignedBigInteger('id_level')->default(3);
            $table->timestamps();
            $table->foreign('id_level')->references('id')->on('levels');
        });

        DB::table('users')->insert([
            [
                'nama_user' => 'Ilham Admin',
                'username' => 'ilhamadmin',
                'password' => Hash::make('12345678'),
                'id_level' => '3',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
