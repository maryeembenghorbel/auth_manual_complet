<?php
//used by viewer!!!!!!!!!!!!!
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('equipment', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type');
        $table->string('serial_number')->unique();
        $table->string('state');
        $table->integer('quantity')->default(1);
        $table->decimal('price', 10, 2)->nullable();
        $table->date('purchase_date')->nullable();
        $table->string('image')->nullable();
        $table->string('brand')->nullable();   
        $table->string('model')->nullable();   
        $table->string('supplier')->nullable(); 
        $table->date('warranty')->nullable();    
        $table->timestamps();
    });
}
};