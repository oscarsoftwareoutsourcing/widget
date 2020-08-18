<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('widget_id')->unsigned();
            $table->string('origin');
            $table->longText('info_data');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->foreign('widget_id')->references('id')->on('widget');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget_data');
    }
}
