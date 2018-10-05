<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExhibitorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('exhibitors', function (Blueprint $table) {
            $table->string('booth_no', 50)->default('');
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
            $table->dropColumn('booth_no');
        });
    }
}






