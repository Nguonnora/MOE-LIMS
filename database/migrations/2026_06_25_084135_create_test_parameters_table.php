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
            $table->string('code')->unique();          // e.g., PH-001, NUT-002
            $table->string('name');                    // Full test name
            $table->string('category')->nullable();    // Physical, Chemical, Biological, etc.
            $table->string('unit')->nullable();        // mg/L, pH, %, ppm, etc.
            $table->string('method')->nullable();      // Standard method name
            $table->string('reference_method')->nullable(); // Reference standard
            $table->decimal('detection_limit', 15, 6)->nullable();
            $table->decimal('quantification_limit', 15, 6)->nullable();
            $table->string('accreditation')->nullable(); // ISO, etc.
            $table->boolean('is_subcontracted')->default(false);
            $table->decimal('default_price', 10, 2)->default(0); // Suggested price
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_parameters');
    }
};