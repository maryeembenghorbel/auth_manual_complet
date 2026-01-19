<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('storage_locations', function (Blueprint $table) {
        $table->id();
        $table->string('name'); 
        $table->integer('grid_row_index');
        $table->integer('grid_column_index');
        $table->timestamps();
    });

    Schema::table('equipment', function (Blueprint $table) {
        $table->foreignId('storage_location_id')
              ->nullable()
              ->constrained('storage_locations')
              ->onUpdate('cascade')
              ->onDelete('set null'); 
    });
}

public function down()
{
     Schema::table('equipment', function (Blueprint $table) {
        $table->dropForeign(['storage_location_id']);
        $table->dropColumn('storage_location_id');
    });
    Schema::dropIfExists('storage_locations');
}
};
