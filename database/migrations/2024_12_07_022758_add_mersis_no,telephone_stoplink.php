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
            $table->string('phone_number_netgsm')->nullable();
            $table->string('mersis_no')->nullable();
            $table->string('stop_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_owners', function (Blueprint $table) {
            $table->dropColumn(['phone_number_netgsm','mersis_no','stop_link']);
        });
    }
};
