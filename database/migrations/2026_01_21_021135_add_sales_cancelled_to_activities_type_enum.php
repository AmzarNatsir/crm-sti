<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM(
            'Call', 'Email', 'Meeting', 'Task', 'WhatsApp', 'Visit', 'status_update', 
            'promote_to_customer', 'schedule_delivery_open', 'schedule_delivery_completed',
            'schedule_delivery_approved', 'schedule_delivery_rejected', 'sales', 'sales_cancelled'
        )");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM(
            'Call', 'Email', 'Meeting', 'Task', 'WhatsApp', 'Visit', 'status_update', 
            'promote_to_customer', 'schedule_delivery_open', 'schedule_delivery_completed',
            'schedule_delivery_approved', 'schedule_delivery_rejected', 'sales'
        )");
    }
};
