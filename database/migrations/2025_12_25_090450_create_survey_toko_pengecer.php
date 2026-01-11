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
        Schema::create('survey_toko_pengecer', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string('profil_NamaToko')->nullable();
            $table->string('profil_Alamat')->nullable();
            $table->string('profil_KanalPenjualan')->nullable();
            $table->string('profil_VolumePenjualanBulanan')->nullable();
            $table->string('profil_MerekYangDijual')->nullable();
            $table->string('kebutuhanKetertarikan_ProdukSti')->nullable();
            $table->string('kebutuhanKetertarikan_Margin')->nullable();
            $table->string('kebutuhanKetertarikan_SyaratPembayaran')->nullable();
            $table->string('kebutuhanKetertarikan_DukunganPromosi')->nullable();
            $table->string('kesediaanProgram_DisplayMateri')->nullable();
            $table->string('kesediaanProgram_StokAwal')->nullable();
            $table->string('kesediaanProgram_DemoPlot')->nullable();
            $table->string('kesediaanProgram_ProgramPoin')->nullable();
            $table->boolean('rencanaKerjasama_POAwal')->default(false);
            $table->string('rencanaKerjasama_POAwal_Estimasi')->nullable();
            $table->date('rencanaKerjasama_JadwalPelatihan')->nullable();
            $table->string('rencanaKerjasama_TargetTigaBulan')->nullable();
            $table->string('memberGetMember_Nama')->nullable();
            $table->string('memberGetMember_Kontak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_toko_pengecer');
    }
};
