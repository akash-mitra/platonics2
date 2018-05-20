<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('commentable_id')->unsigned();
            $table->string('commentable_type', 30);
            $table->text('body');
            $table->integer('vote')->nullable();
            $table->integer('offensive_index')->unsigned()->nullable();
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
        Schema::dropIfExists('comments');
    }
}
