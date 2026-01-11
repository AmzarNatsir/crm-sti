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
            $table->enum('type', ['Call', 'Email', 'Meeting', 'Task', 'WhatsApp', 'Visit'])->change();
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->enum('type', ['call', 'visit', 'whatsapp', 'email', 'meeting'])->change();
            $table->enum('status', ['pending', 'done'])->change();
        });
    }
};
