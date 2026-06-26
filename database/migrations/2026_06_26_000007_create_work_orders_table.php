<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number')->unique();
            $table->date('reception_date');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->text('project_description')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('purpose_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('sample_matrix')->nullable();
            $table->integer('amount_of_sample')->default(0);
            $table->enum('status', ['draft', 'submitted', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('invoice_number')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
};