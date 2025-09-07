<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ubah model_id di model_has_roles
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_id', 36)->change();
        });

        // Ubah model_id di model_has_permissions
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('model_id', 36)->change();
        });
    }

    public function down()
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('model_id')->change();
        });
    }
};