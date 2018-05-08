<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTahfidzLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahfidz_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('attendee_log_id');
            $table->foreign('attendee_log_id')->references('id')->on('attendee_logs')->onDelete('cascade');
            $table->integer('surah_number');
            $table->foreign('surah_number')->references('surah_number')->on('surah_entries')->onDelete('cascade');
            $table->integer('ayah_number');


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
        Schema::dropIfExists('tahfidz_logs');
    }
}
