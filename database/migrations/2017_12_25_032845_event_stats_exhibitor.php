<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventStatsExhibitor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_stats_exhibitors', function ($table) {
            $table->increments('id')->index();
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('unique_views')->default(0);
            $table->integer('tickets_sold')->default(0);

            $table->decimal('sales_volume', 13, 2)->default(0);
            $table->decimal('organiser_fees_volume', 13, 2)->default(0);

            $table->unsignedInteger('event_id')->index();

            $table->string('access_code', 50)->default('');  

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('event_stats_exhibitors');
    }
}
