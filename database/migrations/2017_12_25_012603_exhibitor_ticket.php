<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExhibitorTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::table('exhibitor_tickets', function (Blueprint $table) {
            $table->unsignedInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('exhibitor_tickets', function (Blueprint $table) {
            $table->dropColumn('event_id');
        });

    }
}
