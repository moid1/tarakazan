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
        Schema::create('business_owner_campaign_s_m_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_owner_id')->constrained()->onDelete('cascade');
            $table->text('sms')->nullable();
            $table->datetime('delivery_date')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_owner_campaign_s_m_s');
    }
};
