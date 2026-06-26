<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('report_number')->unique();
            $table->date('report_date');
            $table->text('executive_summary')->nullable();
            $table->text('methodology')->nullable();
            $table->text('conclusions')->nullable();
            $table->text('recommendations')->nullable();
            $table->foreignId('generated_by')->constrained('users');
            $table->string('pdf_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};