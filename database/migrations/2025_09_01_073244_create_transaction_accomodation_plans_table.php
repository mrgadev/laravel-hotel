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
        Schema::create('transaction_accomodation_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('accomodation_plan_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_accomodations_plans');
    }
};
