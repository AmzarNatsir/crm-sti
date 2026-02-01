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
        Schema::create('ref_param_wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('zona', 50)->nullable();
            $table->char('province_id', 2);
            $table->double('ckm')->nullable();
            $table->double('ct')->nullable();
            $table->double('tarif_min')->nullable();
            $table->float('alpha_max_retail')->nullable();
            $table->float('alpha_max_reseller')->nullable();
            $table->timestamps();
            $table->foreign('province_id')->references('id')->on('provinces')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_param_wilayah');
    }
};
