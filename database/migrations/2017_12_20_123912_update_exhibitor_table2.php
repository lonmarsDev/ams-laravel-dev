<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExhibitorTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
          Schema::table('exhibitors', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('exhibitors', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }


}

