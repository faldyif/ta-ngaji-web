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
            $table->unsignedInteger('kompetensi_kurikulum_id');
            $table->foreign('kompetensi_kurikulum_id')->references('id')->on('kompetensi_kurikulum_tahsins')->onDelete('cascade');
            $table->double('percentage');
            $table->integer('grade');

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
        Schema::dropIfExists('tahsin_logs');
    }
}
