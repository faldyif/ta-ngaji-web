<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTahsinLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahsin_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('attendee_log_id');
            $table->foreign('attendee_log_id')->references('id')->on('attendee_logs')->onDelete('cascade');
            $table->unsignedInteger('tahsin_curriculum_competence_id');
            $table->foreign('tahsin_curriculum_competence_id')->references('id')->on('tahsin_curriculum_competences')->onDelete('cascade');
            $table->double('percentage');
            $table->integer('grade');

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
        Schema::dropIfExists('tahsin_logs');
    }
}
