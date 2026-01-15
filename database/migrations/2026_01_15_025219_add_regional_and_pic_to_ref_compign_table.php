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
        Schema::table('ref_compign', function (Blueprint $table) {
            $table->string('company_area_province')->nullable();
            $table->string('company_area_regency')->nullable();
            $table->string('company_area_district')->nullable();
            $table->string('company_area_village')->nullable();
            $table->unsignedBigInteger('pic_employee_id')->nullable();
            $table->foreign('pic_employee_id')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ref_compign', function (Blueprint $table) {
            $table->dropForeign(['pic_employee_id']);
            $table->dropColumn([
                'company_area_province',
                'company_area_regency',
                'company_area_district',
                'company_area_village',
                'pic_employee_id'
            ]);
        });
    }
};
