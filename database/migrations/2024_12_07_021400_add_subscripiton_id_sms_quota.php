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
        Schema::table('s_m_s_quotas', function (Blueprint $table) {
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('s_m_s_quotas', function (Blueprint $table) {
            $table->dropColumn('subscription_id');
        });
    }
};
