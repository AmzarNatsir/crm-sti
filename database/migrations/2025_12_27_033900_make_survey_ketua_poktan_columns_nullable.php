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
        Schema::table('survey_ketua_poktan', function (Blueprint $table) {
             // Make profil_JumlahAnggota nullable (removing default if any, or just changing)
            $table->integer('profil_JumlahAnggota')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_ketua_poktan', function (Blueprint $table) {
            $table->integer('profil_JumlahAnggota')->default(0)->change();
        });
    }
};
