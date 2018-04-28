<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurahEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surah_entries', function (Blueprint $table) {
            $table->integer('surah_number');
            $table->primary('surah_number');
            $table->integer('total_ayah');
            $table->string('surah_name');
            $table->string('classification');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surah_entries');
    }
}
