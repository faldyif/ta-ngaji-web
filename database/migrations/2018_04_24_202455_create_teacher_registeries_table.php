<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherRegisteriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_registeries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('teacher_level_id');
            $table->foreign('teacher_level_id')->references('id')->on('teacher_levels')->onDelete('cascade');
            $table->dateTime('registered_from');
            $table->integer('teacher_competence');
            $table->text('home_short_name')->nullable();
            $table->double('home_latitude')->nullable();
            $table->double('home_longitude')->nullable();
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
        Schema::dropIfExists('teacher_registeries');
    }
}
