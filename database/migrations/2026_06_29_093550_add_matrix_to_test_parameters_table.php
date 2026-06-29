<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('test_parameters', function (Blueprint $table) {
            $table->string('matrix')->nullable()->after('category')->comment('Liquid, Solid, Gas, or null for all');
        });
    }

    public function down()
    {
        Schema::table('test_parameters', function (Blueprint $table) {
            $table->dropColumn('matrix');
        });
    }
};