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
        Schema::table('survey_bagian_umum', function (Blueprint $table) {
            $table->string('noIdentity')->nullable()->after('namaLengkap');
            $table->date('tglLahir')->nullable()->after('noIdentity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_bagian_umum', function (Blueprint $table) {
            //
        });
    }
};
