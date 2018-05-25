<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherFreeTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_free_times', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('teacher_registeries')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('fixed_place');
            $table->string('short_place_name')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

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
        Schema::dropIfExists('teacher_free_times');
    }
}
