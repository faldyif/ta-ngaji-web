<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('from_user_id');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('to_user_id');
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('amount');
            $table->string('message');
            $table->integer('transaction_type'); // 1 for topup, 2 for direct normal transfer
            $table->integer('status')->default(1); // 1 for success, 2 for reversal

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
        Schema::dropIfExists('transfer_transactions');
    }
}
