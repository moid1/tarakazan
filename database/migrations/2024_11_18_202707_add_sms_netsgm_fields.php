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
        Schema::table('business_owners', function (Blueprint $table) {
            $table->string('sms_user_code')->nullable();
            $table->string('sms_user_password')->nullable();
            $table->string('sms_message_header')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_owners', function (Blueprint $table) {
            $table->dropColumn(['sms_user_code', 'sms_user_password', 'sms_message_header']);
        });
    }
};
