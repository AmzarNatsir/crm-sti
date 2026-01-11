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
            $table->string('village')->nullable()->after('city');
            $table->string('village_code')->nullable()->after('village');
            $table->string('sub_district')->nullable()->after('village_code');
            $table->string('sub_district_code')->nullable()->after('sub_district');
            $table->string('district')->nullable()->after('sub_district_code');
            $table->string('district_code')->nullable()->after('district');
            $table->string('province')->nullable()->after('district_code');
            $table->string('province_code')->nullable()->after('province');
            $table->string('point_coordinate')->nullable()->after('province_code');
            $table->string('photo_profile')->nullable()->after('point_coordinate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
