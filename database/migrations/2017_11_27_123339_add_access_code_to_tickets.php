<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessCodeToTickets extends Migration
{
    /**
     * Run the migrations.
     * #steven
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function(Blueprint $table) {
            $table->boolean('with_accesscodes')->default(0);
            $table->string('access_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function(Blueprint $table) {
            $table->dropColumn('with_accesscodes');
            $table->dropColumn('access_code');
        });
    }
}
