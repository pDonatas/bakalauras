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
        Schema::create(config('builder.storage.database.prefix') . 'uploads', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('public_id', 50)->unique();
            $table->string('original_file', 512);
            $table->string('mime_type', 50);
            $table->string('server_file', 512)->unique();
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
        Schema::dropIfExists(config('builder.storage.database.prefix') . 'uploads');
    }
};
