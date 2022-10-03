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
        Schema::create(config('builder.storage.database.prefix') . 'settings', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('setting', 50)->unique();
            $table->mediumText('value');
            $table->boolean('is_array');
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
        Schema::dropIfExists(config('builder.storage.database.prefix') . 'settings');
    }
};
