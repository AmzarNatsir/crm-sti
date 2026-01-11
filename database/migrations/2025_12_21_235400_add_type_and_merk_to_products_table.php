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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->nullable()->after('category');
            $table->unsignedBigInteger('merk_id')->nullable()->after('type_id');

            // Foreign keys inside the same table call or separate if needed (common_type, common_merk)
            // Assuming tables common_type/common_merk exist
            // $table->foreign('type_id')->references('id')->on('common_type'); // Optional: Add constraints if desired
            // $table->foreign('merk_id')->references('id')->on('common_merk'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
             $table->dropColumn(['type_id', 'merk_id']);
        });
    }
};
