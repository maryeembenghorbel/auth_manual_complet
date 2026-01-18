<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('name');
    $table->string('brand');
    $table->string('model');
    $table->string('type');
    $table->string('serial_number')->nullable();
    $table->enum('status', ['neuf','en_service','en_panne','maintenance','hs']);
    $table->integer('quantity')->default(0);
    $table->integer('stock_min')->default(5);
    $table->decimal('price', 10, 2)->nullable();
    $table->date('purchase_date')->nullable();
    $table->date('warranty_end')->nullable();
    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
