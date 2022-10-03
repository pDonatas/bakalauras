<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('builder.storage.database.prefix') . 'pages', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name', 256);
            $table->string('title', 256);
            $table->string('route', 512)->unique();
            $table->string('layout', 256);
            $table->longText('data')->nullable();
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
        Schema::dropIfExists(config('builder.storage.database.prefix') . 'pages');
    }
};
