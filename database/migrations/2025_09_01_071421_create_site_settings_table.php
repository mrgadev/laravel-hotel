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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('maps_link');
            $table->text('address');
            $table->string('phone');
            $table->string('phone_text');
            $table->integer('payment_deadline')->default(24); // in hours
            $table->timestamps();
            $table->time('checkin_time')->nullable();
            $table->time('checkout_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
