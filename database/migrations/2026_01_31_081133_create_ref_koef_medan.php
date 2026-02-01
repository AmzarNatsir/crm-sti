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
        Schema::create('ref_koef_medan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_medan', 10)->nullable();
            $table->string('description', 100)->nullable();
            $table->decimal('km')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_koef_medan');
    }
};
