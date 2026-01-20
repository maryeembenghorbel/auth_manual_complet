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
       Schema::create('equipment', function (Blueprint $table) {
    $table->id();
    $table->string('name');               // Nom du matériel
    $table->string('brand')->nullable();  // Marque
    $table->string('model')->nullable();  // Modèle
    $table->enum('type', ['PC', 'Écran', 'Routeur', 'Switch', 'Imprimante', 'Autre']);
    $table->string('serial_number')->unique();
    $table->enum('state', ['Neuf', 'En service', 'En panne', 'Maintenance', 'HS'])->default('Neuf');
    $table->string('supplier')->nullable();
    $table->integer('quantity')->default(1);
    $table->decimal('price', 10, 2)->nullable();
    $table->date('purchase_date')->nullable();
    $table->date('warranty')->nullable();
    $table->string('image')->nullable();  // chemin image
    $table->softDeletes();                // soft delete
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
