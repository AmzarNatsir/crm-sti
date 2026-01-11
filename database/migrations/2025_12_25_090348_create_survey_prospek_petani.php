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
        Schema::create('survey_prospek_petani', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string('tantanganUtamaSaatIni')->nullable();
            $table->string('tantanganUtamaSaatIni_Lainnya')->nullable();
            $table->integer('dampakHasil_Penurunan')->default(0);
            $table->integer('dampakHasil_Area')->default(0);
            $table->string('solusi_ProdukMerek')->nullable();
            $table->string('solusi_Dosis')->nullable();
            $table->string('solusi_CaraPakai')->nullable();
            $table->integer('solusi_Hasil')->default(0);
            $table->string('solusi_AlasanPuasTidak')->nullable();
            $table->double('rencanaTanamAnggaran_Budget')->default(0);
            $table->float('rencanaTanamAnggaran_TargetHasil')->default(0);
            $table->string('rencanaTanamAnggaran_BatasWaktuTanam')->nullable();
            $table->string('perilakuPembelian_TokoLangganan')->nullable();
            $table->string('perilakuPembelian_Pengepul')->nullable();
            $table->string('perilakuPembelian_PengambilKeputusan')->nullable();
            $table->boolean('minatProgramPembayaranPerpanen')->default(false);
            $table->float('minatProgramPembayaranPerpanen_KisaranHasil')->default(0);
            $table->float('minatProgramPembayaranPerpanen_FrekuensiPanen')->default(0);
            $table->string('minatProgramPembayaranPerpanen_BuktiHasil')->nullable();
            $table->string('minatProgramPembayaranPerpanen_PreferensiTenor')->nullable();
            $table->boolean('minatProgramPembayaranPerpanen_Kesediaan')->default(false);
            $table->boolean('minatProgramRewardMemberGetMember')->default(false);
            $table->string('minatProgramRewardMemberGetMember_TopikReward')->nullable();
            $table->boolean('kebutuhanPendampinganAgronomis')->default(false);
            $table->string('kebutuhanPendampinganAgronomis_Topik')->nullable();
            $table->date('kebutuhanPendampinganAgronomis_WaktuKunjungan')->nullable();
            $table->string('kesiapanUjiCobaProdukSti')->nullable();
            $table->string('kesiapanUjiCobaProdukSti_AlasanTidakBerminat')->nullable();
            $table->string('komitmenAwal')->nullable();
            $table->string('dokumentasi_Photo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_prospek_petani');
    }
};
