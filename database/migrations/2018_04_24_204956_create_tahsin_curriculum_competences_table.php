<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKompetensiKurikulumTahsinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahsin_curriculum_competences', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('kurikulum_id');
            $table->foreign('kurikulum_id')->references('id')->on('kompetensi_kurikulum_tahsins')->onDelete('cascade');
            $table->string('kompetensi');
            $table->text('materi_pokok');
            $table->integer('waktu_menit');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tahsin_curriculum_competences');
    }
}
