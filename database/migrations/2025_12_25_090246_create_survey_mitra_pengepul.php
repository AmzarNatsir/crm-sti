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
        Schema::create('survey_mitra_pengepul', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->string("profil_NamaUsaha")->nullable();
            $table->string("profil_KomoditasUtama")->nullable();
            $table->string("profil_WilayahJangkauan")->nullable();
            $table->boolean("profil_Musiman")->default(true);
            $table->string("kebutuhan_KonsistensiPasokan")->nullable();
            $table->string("kebutuhan_Kualitas")->nullable();
            $table->string("kebutuhan_DukunganBudidaya")->nullable();
            $table->string("modelKerjasama_SkemaKemitraan")->nullable();
            $table->string("modelKerjasama_KeterlibatanProgram")->nullable();
            $table->string("modelKerjasama_DukunganLogistikEdukasi")->nullable();
            $table->boolean("potensiIntegrasiDataPanen")->default(false);
            $table->date('komitmenAwal_PertemuanSelanjutnya')->nullable();
            $table->string('komitmenAwal_DataYangDibutuhkan')->nullable();
            $table->string('komitmenAwal_PicTeknis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_mitra_pengepul');
    }
};
