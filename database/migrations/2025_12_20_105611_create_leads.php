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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();
            $table->foreignId('customer_id')
            ->unique()
            ->constrained('customers')
            ->cascadeOnDelete();

            $table->foreignId('assigned_to')
            ->constrained('users')
            ->cascadeOnUpdate()
            ->restrictOnDelete();

            $table->enum('status', [
                'new',
                'contacted',
                'qualified',
                'proposal',
                'won',
                'lost'
            ])->default('new');

            $table->unsignedInteger('score')->default(0);
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->date('expected_close_date')->nullable();
            $table->string('lost_reason')->nullable();

            $table->timestamps();

            $table->index(['assigned_to', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
