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
        Schema::create('survey_statistik_konteks_pertanian', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string('curahHujan')->nullable();
            $table->string('kejadianEkstrem')->nullable();
            $table->date('tanggal')->nullable();
            $table->double('harga_TrenHargaPupukBenihPestisida')->default(0);
            $table->double('harga_HargaJualHasilPanen')->default(0);
            $table->string('perubahanPraktikBudidaya_VarietasBaru')->nullable();
            $table->string('perubahanPraktikBudidaya_PerubahanTeknik')->nullable();
            $table->string('perubahanPraktikBudidaya_PenggunaanMesin')->nullable();
            $table->string('sumberInformasiPetani_Media')->nullable();
            $table->string('sumberInformasiPetani_TokohLokal')->nullable();
            $table->string('sumberInformasiPetani_Penyuluh')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_statistik_konteks_pertanian');
    }
};
