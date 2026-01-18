<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users'); // qui utilise le matériel
            $table->string('location')->nullable(); // bureau ou service
            $table->enum('status', ['attribué','retourné'])->default('attribué');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('assignments');
    }
};

