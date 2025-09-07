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
        Schema::table('transactions', function (Blueprint $table) {
            // Add payment method detail column to store specific Flip payment method
            $table->string('payment_method_detail')->nullable()->after('payment_method');
            
            // Add admin fee column to track fees separately
            $table->decimal('admin_fee', 10, 2)->default(0)->after('total_price');
            
            // Add room number column if not exists
            if (!Schema::hasColumn('transactions', 'room_number')) {
                $table->integer('room_number')->nullable()->after('room_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method_detail', 'admin_fee']);
            
            // Only drop room_number if it was added by this migration
            // Be careful with this in production
            if (Schema::hasColumn('transactions', 'room_number')) {
                $table->dropColumn('room_number');
            }
        });
    }
};