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
            // Geo columns – store API IDs as nullable integers (no foreign keys)
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->enum('coordinate_system', ['DD', 'UTM', 'N/A'])->default('N/A');
            $table->string('coordinate_x')->nullable();
            $table->string('coordinate_y')->nullable();
            $table->enum('status', ['received', 'in_progress', 'results_entered', 'approved', 'reported'])->default('received');
            $table->timestamps();

            // Note: No foreign keys to provinces, districts, etc.
        });
    }

    public function down()
    {
        Schema::dropIfExists('samples');
    }
};