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
         Schema::create('vulnerabilities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('scan_id')->constrained()->onDelete('cascade');
        $table->string('cve_id');
        $table->string('service')->nullable();
        $table->string('version')->nullable();
        $table->float('cvss_score');
        $table->string('severity'); // critical | high | medium | low
        $table->string('state')->default('open'); // open | fixed | accepted
        $table->text('analyst_comment')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};
