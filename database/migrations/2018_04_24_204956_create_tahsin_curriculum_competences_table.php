<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTahsinCurriculumCompetencesTable extends Migration
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

            $table->unsignedInteger('tahsin_curriculum_id');
            $table->foreign('tahsin_curriculum_id')->references('id')->on('tahsin_curriculums')->onDelete('cascade');
            $table->string('competence');
            $table->text('subject_matter');
            $table->unsignedInteger('time_required');

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
