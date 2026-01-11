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
        Schema::table('survey_pelanggan_sti', function (Blueprint $table) {
            $table->string('produkStiYangDigunakan_Nama')->nullable()->change();
            $table->string('produkStiYangDigunakan_Batch')->nullable()->change();
            $table->date('produkStiYangDigunakan_TanggalApplikasi')->nullable()->change();
            $table->string('produkStiYangDigunakan_DosisCaraPakai')->nullable()->change();
            $table->integer('perkembanganTanaman_Pertumbuhan')->nullable()->change();
            $table->integer('perkembanganTanaman_HijauDaun')->nullable()->change();
            $table->integer('perkembanganTanaman_Akar')->nullable()->change();
            $table->integer('perkembanganTanaman_BungaPolongBuah')->nullable()->change();
            $table->string('kondisiCuaca')->nullable()->change();
            $table->string('kondisiCuaca_Catatan')->nullable()->change();
            $table->string('masalahYangMuncul_Jenis')->nullable()->change();
            $table->float('masalahYangMuncul_LuasTerdampak')->nullable()->change();
            $table->integer('masalahYangMuncul_Keparahan')->nullable()->change();
            $table->string('masalahYangMuncul_Photo')->nullable()->change();
            $table->string('tindakanKorektif_Apa')->nullable()->change();
            $table->date('tindakanKorektif_Kapan')->nullable()->change();
            $table->integer('tindakanKorektif_HasilAwal')->nullable()->change();
            $table->boolean('butuhPendampingan')->nullable()->change();
            $table->date('butuhPendampingan_Jadwal')->nullable()->change();
            $table->string('butuhPendampingan_Lokasi')->nullable()->change();
            $table->string('butuhPendampingan_Tujuan')->nullable()->change();
            $table->float('perkiraanHasil')->nullable()->change();
            $table->date('rencanaPanen')->nullable()->change();
            $table->integer('kepuasanTerhadapProdukLayanan_Nilai')->nullable()->change();
            $table->string('kepuasanTerhadapProdukLayanan_Alasan')->nullable()->change();
            $table->boolean('minatIkutLanjutProgramReward')->nullable()->change();
            $table->boolean('memberGetMember')->nullable()->change();
            $table->string('memberGetMember_Referal')->nullable()->change();
            $table->string('nextStep_TindakLanjut')->nullable()->change();
            $table->date('nextStep_WaktuFollowup')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_pelanggan_sti', function (Blueprint $table) {
            //
        });
    }
};
