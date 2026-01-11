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
        Schema::table('survey_penyelesaian_masalah', function (Blueprint $table) {
            $table->string('dampak_LuasAreaTerdampak')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_penyelesaian_masalah', function (Blueprint $table) {
            $table->integer('dampak_LuasAreaTerdampak')->default(0)->change();
        });
    }
};
