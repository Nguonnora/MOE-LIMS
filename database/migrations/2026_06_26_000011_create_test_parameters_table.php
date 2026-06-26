<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit')->nullable();
            $table->string('method')->nullable();
            $table->string('reference_method')->nullable();
            $table->decimal('detection_limit', 15, 6)->nullable();
            $table->decimal('quantification_limit', 15, 6)->nullable();
            $table->string('accreditation')->nullable();
            $table->boolean('is_subcontracted')->default(false);
            $table->decimal('default_price', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_parameters');
    }
};