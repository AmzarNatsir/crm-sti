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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->string('employee_number', 50)->unique();
            $table->string('identitiy_number', 50)->unique();
            $table->string('name', 100);
            $table->string('place_of_birth', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('email', 100)->unique();
            $table->string('phone', 100)->nullable();
            $table->text('address', 200)->nullable();
            $table->foreignId('positionId')
            ->nullable()
            ->constrained('common_position')
            ->nullOnDelete()
            ->cascadeOnUpdate();
            $table->date('hire_date')->nullable();
            $table->date('join_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'resigned'])->default('active');
            $table->double('salary')->default(0);
            $table->string('photo', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
