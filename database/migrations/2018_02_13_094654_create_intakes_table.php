<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intakes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pill')->unsigned();
            $table->time('timeOfIntake');
        });

        Schema::table('intakes', function($table){
            $table->foreign('id_pill')->references('id')->on('pills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('intakes');
    }
}
