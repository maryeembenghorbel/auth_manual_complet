<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('status')->default('reviewed')->after('ip_address');
            $table->string('risk_level')->default('low')->after('status');
            $table->integer('critical_cve_count')->default(0)->after('risk_level');
            $table->decimal('critical_score_cumul', 8, 2)->default(0)->after('critical_cve_count');
            $table->timestamp('last_critical_scan_at')->nullable()->after('critical_score_cumul');
            
            $table->index('status');
            $table->index('risk_level');
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['risk_level']);
            $table->dropColumn([
                'status',
                'risk_level',
                'critical_cve_count',
                'critical_score_cumul',
                'last_critical_scan_at'
            ]);
        });
    }
};