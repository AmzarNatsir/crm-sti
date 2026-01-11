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
        Schema::create('survey_penutup_ringkasan', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
           $table->foreignId('surveyId')
            ->nullable()
            ->constrained('survey_bagian_umum')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->text('ringkasanKebutuhanSolusi')->nullable();
            $table->string('komitmenTindakLanjut_Apa')->nullable();
            $table->string('komitmenTindakLanjut_OlehSiapa')->nullable();
            $table->date('komitmenTindakLanjut_KapanTanggal')->nullable();
            $table->string('komitmenTindakLanjut_KapanJam')->nullable();
            $table->date('jadwalFollowup_Tanggal')->nullable();
            $table->time('jadwalFollowup_Jam')->nullable();
            $table->string('jadwalFollowup_Kanal')->nullable();
            $table->string('dokumentasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_penutup_ringkasan');
    }
};
