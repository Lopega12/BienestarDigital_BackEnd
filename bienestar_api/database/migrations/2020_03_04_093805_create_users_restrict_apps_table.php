<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRestrictAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_restrict_apps', function (Blueprint $table) {
            //$table->bigIncrements('id');
            $table->primary(['id_user','id_app']);
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_app');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_app')->references('id')->on('apps')->onDelete('cascade');
            $table->time('max_use_time');
            $table->time('start_time');
            $table->time('finish_time');
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
        Schema::dropIfExists('users_restrinct_apps');
    }
}
