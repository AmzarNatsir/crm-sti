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
        Schema::create('survey_pelanggan_sti', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string('produkStiYangDigunakan_Nama')->nullable();
            $table->string('produkStiYangDigunakan_Batch')->nullable();
            $table->date('produkStiYangDigunakan_TanggalApplikasi')->nullable();
            $table->string('produkStiYangDigunakan_DosisCaraPakai')->nullable();
            $table->integer('perkembanganTanaman_Pertumbuhan')->default(0);
            $table->integer('perkembanganTanaman_HijauDaun')->default(0);
            $table->integer('perkembanganTanaman_Akar')->default(0);
            $table->integer('perkembanganTanaman_BungaPolongBuah')->default(0);
            $table->string('kondisiCuaca')->nullable();
            $table->string('kondisiCuaca_Catatan')->nullable();
            $table->string('masalahYangMuncul_Jenis')->nullable();
            $table->float('masalahYangMuncul_LuasTerdampak')->default(0);
            $table->integer('masalahYangMuncul_Keparahan')->default(0);
            $table->string('masalahYangMuncul_Photo')->nullable();
            $table->string('tindakanKorektif_Apa')->nullable();
            $table->date('tindakanKorektif_Kapan')->nullable();
            $table->integer('tindakanKorektif_HasilAwal')->default(0);
            $table->boolean('butuhPendampingan')->default(false);
            $table->date('butuhPendampingan_Jadwal')->nullable();
            $table->string('butuhPendampingan_Lokasi')->nullable();
            $table->string('butuhPendampingan_Tujuan')->nullable();
            $table->float('perkiraanHasil')->default(0);
            $table->date('rencanaPanen')->nullable();
            $table->integer('kepuasanTerhadapProdukLayanan_Nilai')->default(0);
            $table->string('kepuasanTerhadapProdukLayanan_Alasan')->nullable();
            $table->boolean('minatIkutLanjutProgramReward')->default(false);
            $table->boolean('memberGetMember')->default(false);
            $table->string('memberGetMember_Referal')->nullable();
            $table->string('nextStep_TindakLanjut')->nullable();
            $table->date('nextStep_WaktuFollowup')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_pelanggan_sti');
    }
};
