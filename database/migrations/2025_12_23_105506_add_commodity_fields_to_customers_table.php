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
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('commodity_id')->nullable()->after('type');
            $table->string('identity_no')->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('identity_no');

            $table->foreign('commodity_id')->references('id')->on('ref_commodity')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['commodity_id']);
            $table->dropColumn(['commodity_id', 'identity_no', 'date_of_birth']);
        });
    }
};
