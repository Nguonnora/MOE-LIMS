<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('geo_locations');
    }

    public function down()
    {
        Schema::create('geo_locations', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('code')->index();
            $table->string('parent_code')->nullable()->index();
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->unique(['level', 'code']);
        });
    }
};