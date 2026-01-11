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
        Schema::create('survey_ketua_poktan', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string('profil_Nama')->nullable();
            $table->integer('profil_JumlahAnggota')->default(0);
            $table->string('profil_TotalLuasTanam')->nullable();
            $table->string('profil_KomoditasMayor')->nullable();
            $table->date('agendaBudidaya_KalenderTanam')->nullable();
            $table->string('agendaBudidaya_TantanganUmum')->nullable();
            $table->string('agendaBudidaya_KegiatanKelompok')->nullable();
            $table->string('ketertarikan_SosialisasiProduk')->nullable();
            $table->string('ketertarikan_DemoPlot')->nullable();
            $table->string('ketertarikan_ProgramPendampingan')->nullable();
            $table->string('ketertarikan_SkemaPembelianKolektif')->nullable();
            $table->string('syaratEkspektasi_TransparansiHarga')->nullable();
            $table->string('syaratEkspektasi_DukunganTeknis')->nullable();
            $table->string('syaratEkspektasi_RewardKelompok')->nullable();
            $table->date('aksiAwal_JadwalSosialisasi')->nullable();
            $table->string('aksiAwal_LahanDemo')->nullable();
            $table->text('aksiAwal_Anggota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_ketua_poktan');
    }
};
