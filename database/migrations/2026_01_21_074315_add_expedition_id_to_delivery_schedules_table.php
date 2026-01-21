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
        Schema::table('delivery_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('expedition_id')->nullable()->after('employee_id');
            $table->foreign('expedition_id')->references('id')->on('expedition')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_schedules', function (Blueprint $table) {
            $table->dropForeign(['expedition_id']);
            $table->dropColumn('expedition_id');
        });
    }
};
