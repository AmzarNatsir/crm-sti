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
        Schema::table('survey_statistik_konteks_pertanian', function (Blueprint $table) {
            $table->string('harga_TrenHargaPupukBenihPestisida')->nullable()->change();
            $table->string('harga_HargaJualHasilPanen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_statistik_konteks_pertanian', function (Blueprint $table) {
            $table->double('harga_TrenHargaPupukBenihPestisida')->default(0)->change();
            $table->double('harga_HargaJualHasilPanen')->default(0)->change();
        });
    }
};
