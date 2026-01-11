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
        Schema::create('ref_compign', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('name'); 
            $table->text('description'); 
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target_sales')->default(0);
            $table->double('target_revenue')->default(0);
            $table->string('target_segment')->nullable();
            $table->string('target_area')->nullable();
            $table->string('target_product')->nullable();
            $table->string('channel')->nullable();
            $table->string('target_promotion')->nullable();
            $table->double('badget')->default(0);
            $table->integer('actual_sales')->nullable();
            $table->double('actual_revenue')->nullable();
            $table->float('roi')->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_compign');
    }
};
