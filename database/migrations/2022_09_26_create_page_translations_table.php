<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('builder.storage.database.prefix') . 'page_translations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('page_id');
            $table->string('locale', 50);
            $table->string('title', 255);
            $table->string('route', 255);
            $table->timestamps();

            $table->unique(['page_id', 'locale']);
            $table->foreign('page_id')->references('id')
                ->on(config('builder.storage.database.prefix') . 'pages')
                ->onUpdate('cascade')->onDelete('cascade');

        });

        Schema::table(config('builder.storage.database.prefix') . 'pages', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('route');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('builder.storage.database.prefix') . 'pages', function (Blueprint $table) {
            $table->string('route', 512)->unique()->after('name');
            $table->string('title', 256)->after('name');
        });

        Schema::dropIfExists(config('builder.storage.database.prefix') . 'page_translations');
    }
};
