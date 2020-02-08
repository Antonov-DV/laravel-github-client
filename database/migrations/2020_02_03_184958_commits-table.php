<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commit', function (Blueprint $table) {
            $table->bigIncrements('id')->unique()->unsigned();
            $table->integer('repo_id');
            $table->string('message');
            $table->string('author_name');
            $table->string('sha')->unique();
            $table->json('data');
            $table->timestamps();
            $table->index(['sha']);
            $table->index('repo_id');
            $table->index(['repo_id', 'author_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('commit');
    }
}
