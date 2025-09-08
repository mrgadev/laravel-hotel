<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove old Flip fields
            $table->dropColumn([
                'flip_bill_id',
                'flip_response', 
                'flip_expired_date'
            ]);
            
            // Add new iPaymu fields
            $table->string('ipaymu_transaction_id')->nullable()->after('payment_deadline');
            $table->string('ipaymu_session_id')->nullable()->after('ipaymu_transaction_id');
            $table->json('ipaymu_response')->nullable()->after('ipaymu_session_id');
            $table->datetime('ipaymu_expired_date')->nullable()->after('ipaymu_response');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove iPaymu fields
            $table->dropColumn([
                'ipaymu_transaction_id',
                'ipaymu_session_id',
                'ipaymu_response',
                'ipaymu_expired_date'
            ]);
            
            // Add back Flip fields
            $table->string('flip_bill_id')->nullable()->after('payment_deadline');
            $table->json('flip_response')->nullable()->after('flip_bill_id');
            $table->datetime('flip_expired_date')->nullable()->after('flip_response');
        });
    }
};