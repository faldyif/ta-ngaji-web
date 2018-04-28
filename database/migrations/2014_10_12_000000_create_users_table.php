<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('whatsapp_number')->nullable();
            $table->char('gender');
            $table->integer('role_id')->default(1); // 1 is user, 2 for superadmin
            $table->decimal('credits_amount')->default(0);
            $table->integer('experience_points')->default(0);
            $table->integer('level')->default(1);
            $table->integer('loyalty_points')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
