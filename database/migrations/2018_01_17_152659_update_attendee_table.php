<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttendeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendees', function (Blueprint $table) {
   //         $table->string('eb_barcode', 21)->nullable();
    //        $table->string('eb_ticket_class_name', 50);
    //        $table->string('eb_attendee_id', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn('eb_barcode');
            $table->dropColumn('eb_ticket_class_name');
            $table->dropColumn('eb_attendee_id');
        });

    }
}
