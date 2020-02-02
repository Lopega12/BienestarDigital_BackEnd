<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestrinctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrinctions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nameapp');
            $table->double('maxDiario')->unsigned();
            $table->time('horaInicio')->useCurrent();
            $table->time('horaFinal');
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
        Schema::dropIfExists('restrinctions');
    }
}
