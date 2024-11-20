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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();  // Assuming you have users table
            $table->foreignId('package_id')->constrained();  // Assuming you have users table
            $table->enum('status', ['active', 'inactive', 'cancelled'])->default('active');
            $table->date('start_date');  // Subscription start date
            $table->date('end_date');    // Subscription end date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
