<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_test_id')->constrained()->onDelete('cascade');
            $table->decimal('result_value', 15, 6)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'entered', 'approved', 'rejected'])->default('pending');
            $table->foreignId('entered_by')->nullable()->constrained('users');
            $table->timestamp('entered_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};