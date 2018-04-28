<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuzEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juz_entries', function (Blueprint $table) {
            $table->integer('juz');
            $table->primary('juz');
            $table->integer('from_surah');
            $table->foreign('from_surah')->references('surah_number')->on('surah_entries')->onDelete('cascade');
            $table->integer('from_ayah');
            $table->integer('to_surah');
            $table->foreign('to_surah')->references('surah_number')->on('surah_entries')->onDelete('cascade');
            $table->integer('to_ayah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juz_entries');
    }
}
