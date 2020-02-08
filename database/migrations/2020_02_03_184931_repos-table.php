<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repo', function (Blueprint $table) {
            $table->integer('id')->unique()->unsigned();
            $table->string('name');
            $table->string('owner_login');
            $table->integer('owner_id');
            $table->json('data');
            $table->timestamps();
            $table->index(['name', 'owner_login']);
            $table->index('owner_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('repo');
    }
}
