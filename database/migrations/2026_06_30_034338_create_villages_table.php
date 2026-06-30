<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('commune_code');
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('commune_code')->references('code')->on('communes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('villages');
    }
};