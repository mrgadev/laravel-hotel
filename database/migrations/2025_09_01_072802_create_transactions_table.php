<?php

// use Xendit\Charges;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 'user_id',
        // 'name',
        // 'email',
        // 'phone',
        // 'room_id',
        // 'check_in',
        // 'check_out',
        // 'accomdation_plan_id',
        // 'service_id',
        // 'notes',
        // 'checkin_status',

        // 'invoice',
        // 'payment_url',
        // 'payment_status',
        // 'payment_method',
        // 'total_price',
        // 'promo_id'
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->nullOnDelete();

            $table->date('check_in');
            $table->date('check_out');

            $table->unsignedBigInteger('accomodation_plan_id')->nullable();
            $table->foreign('accomodation_plan_id')->references('id')->on('accomodation_plans')->nullOnDelete();
            
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')->references('id')->on('services')->nullOnDelete();

            $table->longText('notes')->nullable();
            $table->string('checkin_status')->default('Belum Check-in');

            $table->string('invoice')->unique()->nullable();
            $table->string('payment_url')->nullable();
            $table->string('payment_status')->default('Belum bayar');
            $table->string('payment_method')->nullable();
            $table->unsignedBigInteger('total_price')->nullable();
            
            $table->unsignedBigInteger('promo_id')->nullable();
            $table->foreign('promo_id')->references('id')->on('promos')->nullOnDelete();

            $table->string('room_number')->nullable();
            $table->dateTime('checkin_date')->nullable();
            $table->dateTime('checkout_date')->nullable();

            $table->datetime('payment_deadline')->nullable();

            $table->string('flip_bill_id')->nullable();
            $table->json('flip_response')->nullable();
            $table->timestamp('flip_expired_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};