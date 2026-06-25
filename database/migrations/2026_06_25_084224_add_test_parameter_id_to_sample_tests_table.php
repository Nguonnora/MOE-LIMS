<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('sample_tests', function (Blueprint $table) {
        $table->foreignId('test_parameter_id')
              ->nullable()
              ->constrained('test_parameters')
              ->onDelete('set null')
              ->after('sample_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sample_tests', function (Blueprint $table) {
            $table->dropForeign(['test_parameter_id']);
            $table->dropColumn('test_parameter_id');
        });
    }
};
