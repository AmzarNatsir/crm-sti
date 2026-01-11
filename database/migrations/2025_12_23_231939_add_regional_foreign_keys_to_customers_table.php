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
        Schema::table('customers', function (Blueprint $table) {
            $table->char('province_code', 2)->nullable()->change();
            $table->char('district_code', 4)->nullable()->change();
            $table->char('sub_district_code', 7)->nullable()->change();
            $table->char('village_code', 10)->nullable()->change();

            $table->foreign('province_code')->references('id')->on('provinces')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('district_code')->references('id')->on('regencies')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('sub_district_code')->references('id')->on('districts')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('village_code')->references('id')->on('villages')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['province_code']);
            $table->dropForeign(['district_code']);
            $table->dropForeign(['sub_district_code']);
            $table->dropForeign(['village_code']);

            $table->string('province_code')->nullable()->change();
            $table->string('district_code')->nullable()->change();
            $table->string('sub_district_code')->nullable()->change();
            $table->string('village_code')->nullable()->change();
        });
    }
};
