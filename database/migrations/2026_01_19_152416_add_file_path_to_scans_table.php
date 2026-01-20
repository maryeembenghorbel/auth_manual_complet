<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('scans', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('result');
        });
    }

    public function down()
    {
        Schema::table('scans', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }
};