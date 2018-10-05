<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoreApiAddedColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('account_type', 20)->default('');
        });

        Schema::table('attendees', function (Blueprint $table) {
            $table->integer('my_account_id')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->default('');
            $table->string('facebook_id', 50)->default('');
            $table->string('google_id', 50)->default('');
            $table->string('push_token', 50)->default('');

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
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('account_type');
        }); 

        Schema::table('attendees', function (Blueprint $table) {
            $table->dropColumn('my_account_id');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('facebook_id');
            $table->dropColumn('google_id');
            $table->dropColumn('push_token');

        });
    }
}
