<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->string('sample_code')->unique();
            $table->string('sample_type');
            $table->string('sample_matrix')->nullable();
            $table->string('sampling_location')->nullable();
            $table->date('sampling_date');
            $table->time('sampling_time')->nullable();
            $table->string('sampling_method')->nullable();
            $table->string('container_type')->nullable();
            $table->string('preservation_method')->nullable();
            $table->date('received_date');
            $table->time('received_time')->nullable();
            $table->string('received_by')->nullable();
            $table->text('sample_description')->nullable();
            $table->string('sample_condition')->nullable();
            $table->decimal('sample_quantity', 10, 2)->nullable();
            $table->string('quantity_unit')->nullable();
            $table->enum('status', ['received', 'in_progress', 'results_entered', 'approved', 'reported'])->default('received');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('samples');
    }
};