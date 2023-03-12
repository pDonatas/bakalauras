<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->renameColumn('name', 'company_name');
            $table->string('company_code')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('company_name');
            $table->dropColumn('company_code');
            $table->dropColumn('company_address');
            $table->dropColumn('company_phone');
        });
    }
};
