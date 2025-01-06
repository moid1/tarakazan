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
        Schema::table('business_owner_campaign_s_m_s', function (Blueprint $table) {
            $table->string('duration')->default('all');
            $table->string('customers_type')->default('all');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_owner_campaign_s_m_s', function (Blueprint $table) {
            $table->dropColumn(['duration', 'customers_type']);
        });
    }
};
