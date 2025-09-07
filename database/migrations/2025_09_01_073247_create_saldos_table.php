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
        Schema::create('saldos', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->default(0);
            $table->integer('credit')->nullable();
            $table->integer('debit')->nullable();
            $table->text('description')->nullable();

            $table->foreignUuid('user_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignUuid('transaction_id')->nullable()->references('id')->on('transactions')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldos');
    }
};
