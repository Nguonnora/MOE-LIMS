<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check each table and add name if missing
        if (Schema::hasTable('provinces') && !Schema::hasColumn('provinces', 'name')) {
            Schema::table('provinces', function (Blueprint $table) {
                $table->string('name')->after('id');
            });
        }
        if (Schema::hasTable('districts') && !Schema::hasColumn('districts', 'name')) {
            Schema::table('districts', function (Blueprint $table) {
                $table->string('name')->after('province_id');
            });
        }
        if (Schema::hasTable('communes') && !Schema::hasColumn('communes', 'name')) {
            Schema::table('communes', function (Blueprint $table) {
                $table->string('name')->after('district_id');
            });
        }
        if (Schema::hasTable('villages') && !Schema::hasColumn('villages', 'name')) {
            Schema::table('villages', function (Blueprint $table) {
                $table->string('name')->after('commune_id');
            });
        }
    }

    public function down()
    {
        // Optionally drop the columns if needed
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('communes', function (Blueprint $table) {
            $table->dropColumn('name');
        });
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};