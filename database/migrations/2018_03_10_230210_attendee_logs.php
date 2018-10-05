<?php

use Illuminate\Database\Migrations\Migration;


class AttendeeLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendee_logs', function ($table) {
            $table->increments('id')->index();
            $table->dateTime('log');
            $table->integer('log_type'); // 1 = in , 0=out
            $table->unsignedInteger('attendee_id')->index();
            $table->foreign('attendee_id')->references('id')->on('attendees')->onDelete('cascade');
        });

         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attendee_logs');

    }
}
