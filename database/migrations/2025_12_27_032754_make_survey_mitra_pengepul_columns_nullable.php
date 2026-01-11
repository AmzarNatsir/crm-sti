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
        Schema::table('survey_mitra_pengepul', function (Blueprint $table) {
            $table->boolean('profil_Musiman')->nullable()->change();
            $table->boolean('potensiIntegrasiDataPanen')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_mitra_pengepul', function (Blueprint $table) {
            $table->boolean('profil_Musiman')->default(true)->change();
            $table->boolean('potensiIntegrasiDataPanen')->default(false)->change();
        });
    }
};
