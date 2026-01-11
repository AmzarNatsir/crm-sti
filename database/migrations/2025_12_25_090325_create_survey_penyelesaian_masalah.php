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
        Schema::create('survey_penyelesaian_masalah', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string('deskripsi_SejakKapan')->nullable();
            $table->string('deskripsi_TahapanTanaman')->nullable();
            $table->integer('dampak_LuasAreaTerdampak')->default(0);
            $table->string('dampak_EstimasiPotensiPenurunanHasil')->nullable();
            $table->string('riwayatTindakan_ProdukSolusi')->nullable();
            $table->string('riwayatTindakan_Dosis')->nullable();
            $table->date('riwayatTindakan_Tanggal')->nullable();
            $table->string('akarDugaan')->nullable();
            $table->string('akarDugaan_Lainnya')->nullable();
            $table->string('kebutuhanDukungan')->nullable();
            $table->string('rencanaAksiDisepakati_PaketRekomendasi')->nullable();
            $table->string('rencanaAksiDisepakati_Siapa')->nullable();
            $table->date('slaPemantauan_Tanggal')->nullable();
            $table->time('slaPemantauan_Jam')->nullable();
            $table->string('statusTiket', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_penyelesaian_masalah');
    }
};
