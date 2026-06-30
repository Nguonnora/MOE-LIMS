<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('geo_locations', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // province, district, commune, village
            $table->string('code')->index(); // province_code, district_code, etc.
            $table->string('parent_code')->nullable()->index(); // for hierarchical filtering
            $table->string('name_kh')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name')->nullable(); // fallback
            $table->timestamps();

            // Unique combination to avoid duplicates
            $table->unique(['level', 'code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('geo_locations');
    }
};