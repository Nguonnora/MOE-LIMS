<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('sample_code')->unique();
            $table->string('sample_type');
            $table->text('sample_description')->nullable();
            $table->date('sampling_date');
            $table->foreignId('province_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('commune_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('village_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('coordinate_system', ['DD', 'UTM', 'N/A'])->default('N/A');
            $table->string('coordinate_x')->nullable(); // UTM X or Degree N
            $table->string('coordinate_y')->nullable(); // UTM Y or Degree E
            $table->enum('status', ['received', 'in_progress', 'results_entered', 'approved', 'reported'])->default('received');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samples');
    }
};