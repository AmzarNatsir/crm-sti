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
        Schema::table('survey_prospek_petani', function (Blueprint $table) {
            $table->string('tantanganUtamaSaatIni')->nullable()->change();
            $table->string('tantanganUtamaSaatIni_Lainnya')->nullable()->change();
            $table->integer('dampakHasil_Penurunan')->nullable()->change();
            $table->integer('dampakHasil_Area')->nullable()->change();
            $table->string('solusi_ProdukMerek')->nullable()->change();
            $table->string('solusi_Dosis')->nullable()->change();
            $table->string('solusi_CaraPakai')->nullable()->change();
            $table->integer('solusi_Hasil')->nullable()->change();
            $table->string('solusi_AlasanPuasTidak')->nullable()->change();
            $table->double('rencanaTanamAnggaran_Budget')->nullable()->change();
            $table->float('rencanaTanamAnggaran_TargetHasil')->nullable()->change();
            $table->string('rencanaTanamAnggaran_BatasWaktuTanam')->nullable()->change();
            $table->string('perilakuPembelian_TokoLangganan')->nullable()->change();
            $table->string('perilakuPembelian_Pengepul')->nullable()->change();
            $table->string('perilakuPembelian_PengambilKeputusan')->nullable()->change();
            $table->float('minatProgramPembayaranPerpanen_KisaranHasil')->nullable()->change();
            $table->float('minatProgramPembayaranPerpanen_FrekuensiPanen')->nullable()->change();
            $table->string('minatProgramPembayaranPerpanen_BuktiHasil')->nullable()->change();
            $table->string('minatProgramPembayaranPerpanen_PreferensiTenor')->nullable()->change();
            $table->string('minatProgramRewardMemberGetMember_TopikReward')->nullable()->change();
            $table->string('kebutuhanPendampinganAgronomis_Topik')->nullable()->change();
            $table->date('kebutuhanPendampinganAgronomis_WaktuKunjungan')->nullable()->change();
            $table->string('kesiapanUjiCobaProdukSti')->nullable()->change();
            $table->string('kesiapanUjiCobaProdukSti_AlasanTidakBerminat')->nullable()->change();
            $table->string('komitmenAwal')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('survey_prospek_petani', function (Blueprint $table) {
            // Reverting to default(0) or non-nullable if needed
            // For simplicity, we can leave them nullable or try to revert tightly if strict
            $table->integer('dampakHasil_Penurunan')->default(0)->change();
            $table->integer('dampakHasil_Area')->default(0)->change();
        });
    }
};
