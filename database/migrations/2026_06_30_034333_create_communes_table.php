<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('communes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('district_code');
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('district_code')->references('code')->on('districts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('communes');
    }
};