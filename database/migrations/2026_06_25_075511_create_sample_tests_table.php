<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sample_tests', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('sample_id')->constrained()->onDelete('cascade');
            $table->string('test_code')->nullable();
            $table->string('test_name');
            $table->string('test_category')->nullable();
            $table->string('parameter')->nullable();
            $table->string('unit')->nullable();
            $table->string('method')->nullable();
            $table->string('reference_method')->nullable();
            $table->decimal('detection_limit', 15, 6)->nullable();
            $table->decimal('quantification_limit', 15, 6)->nullable();
            $table->string('accreditation')->nullable();
            $table->boolean('is_subcontracted')->default(false);
            $table->string('subcontracted_lab')->nullable();
            $table->date('analysis_start_date')->nullable();
            $table->date('analysis_completion_date')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sample_tests');
    }
};