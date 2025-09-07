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
        Schema::create('frontpage_site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('tagline')->nullable();
            $table->longText('description')->nullable();
            $table->string('our_service_title')->nullable();
            $table->string('our_location_title')->nullable();
            $table->longText('our_location_desc')->nullable();
            $table->string('our_facilities_title')->nullable();
            $table->string('testimonial_title')->nullable();
            $table->string('award_title')->nullable();
            $table->text('award_desc')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_button_link')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->string('hero_cover')->nullable();
            $table->string('service_image')->nullable();
            $table->string('faq_illustration')->nullable();
            $table->string('cta_cover')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontpage_site_settings');
    }
};
