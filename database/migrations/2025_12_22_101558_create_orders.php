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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uid')->unique();

            $table->foreignId('customer_id')
            ->constrained('customers')
            ->cascadeOnDelete();
            $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
            $table->string('invoice_no', 50);
            $table->date('invoice_date');
            $table->decimal('total_amount', 15, 2)->default(0);

            $table->foreignId('payment_method_id')
            ->constrained('common_payment_method');

            $table->integer('compaign_id')->nullable();
             $table->enum('payment_status', [
                'paid',
                'unpaid',
            ])->default('paid');
            $table->decimal('invoice_discount', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
