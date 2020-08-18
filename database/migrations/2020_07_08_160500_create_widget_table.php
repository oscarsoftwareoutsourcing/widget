<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('app_id')->unsigned();;
            $table->integer('user_id')->unsigned();;
            $table->string('name');
            $table->integer('user_id_referred')->nullable();;
            $table->string('url')->nullable();
            $table->string('token');
            $table->longText('script')->nullable();
            $table->longText('parameters')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('app_id')->references('id')->on('apps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widget');
    }
}
