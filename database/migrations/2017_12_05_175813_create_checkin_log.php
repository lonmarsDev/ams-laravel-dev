<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckinLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('checkin_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attendee_id');
            $table->integer('event_id');
            $table->enum('type', ['1', '0']);    
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
        //
         Schema::drop('checkin_log');
    }
}
