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
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('type', [
                'Call', 'Email', 'Meeting', 'Task', 'WhatsApp', 'Visit', 'status_update', 
                'promote_to_customer', 'schedule_delivery_open', 'schedule_delivery_completed'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('type', [
                'Call', 'Email', 'Meeting', 'Task', 'WhatsApp', 'Visit', 'status_update', 
                'promote_to_customer'
            ])->change();
        });
    }
};
