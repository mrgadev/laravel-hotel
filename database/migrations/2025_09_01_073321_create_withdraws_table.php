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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('amount');
            $table->text('notes');
            $table->enum('status', ['Tertunda', 'Disetujui', 'Dibatalkan']);

            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('saldo_id')->constrained()->onDelete('cascade');

            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
