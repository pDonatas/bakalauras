<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('mark');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['shop_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
