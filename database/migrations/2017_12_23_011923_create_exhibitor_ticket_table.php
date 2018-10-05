<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExhibitorTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhibitor_tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('exhibitor_id')->nullable();
            $table->unsignedInteger('ticket_id')->nullable();
            $table->foreign('exhibitor_id')->references('id')->on('exhibitors');
            $table->foreign('ticket_id')->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exhibitor_tickets');

    }
}
