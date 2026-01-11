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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('jenisKontak', 100)->nullable();
            $table->string('namaLengkap', 255)->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->string('noWa', 50)->nullable();
            $table->string('noAlternatif', 50)->nullable();
            $table->string('alamatLahanUsaha', 255)->nullable();
            $table->string('desa')->nullable();
            $table->string('desaKode')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kecamatanKode')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kabupatenKode')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('provinsiKode')->nullable();
            $table->string('titikKoordinat')->nullable();
            $table->string('photoProfile')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
