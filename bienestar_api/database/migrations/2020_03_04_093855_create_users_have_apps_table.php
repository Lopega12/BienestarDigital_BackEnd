<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersHaveAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_have_apps', function (Blueprint $table) {
          //  $table->bigIncrements('id');
            $table->unsignedInteger('app_id');
            $table->dateTime('date');
            $table->string('action');
            $table->double('longitude',8);
            $table->double('latitude',8);
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
        Schema::dropIfExists('users_have_apps');
    }
}
