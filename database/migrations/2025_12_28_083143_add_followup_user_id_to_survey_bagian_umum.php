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
            $table->foreignId('followup_user_id')
                ->nullable()
                ->after('userId')
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_bagian_umum', function (Blueprint $table) {
            $table->dropForeign(['followup_user_id']);
            $table->dropColumn('followup_user_id');
        });
    }
};
