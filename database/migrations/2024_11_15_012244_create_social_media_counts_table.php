<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMediaCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_owner_id'); // The ID of the business owner
            $table->string('platform'); // The platform (facebook, instagram, tiktok)
            $table->integer('count')->default(0); // The count of interactions for that platform
            $table->timestamps(); // created_at, updated_at columns
            $table->unique('platform'); // Ensure there's only one record per platform

            // Foreign key constraint for business_owner_id
            $table->foreign('business_owner_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['business_owner_id', 'platform']); // Ensure one record per platform per business owner
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_media_counts');
    }
}
