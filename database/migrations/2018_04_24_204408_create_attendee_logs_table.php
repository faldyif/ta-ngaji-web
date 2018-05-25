<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->unsignedInteger('student_user_id');
            $table->foreign('student_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('unique_code')->unique();
            $table->dateTime('check_in_time')->nullable();
            $table->text('note_to_student')->nullable();
            $table->text('note_to_next_teacher')->nullable();
            $table->integer('points_earned');
            $table->integer('bonus_points');
            $table->string('bonus_reason')->nullable();

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
        Schema::dropIfExists('attendee_logs');
    }
}
