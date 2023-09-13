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
        Schema::create('user_social_media', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedBigInteger('social_media_id');
            $table->foreign('social_media_id')->references('id')
                ->on('social_media')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('followers');
            $table->string('url');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_social_media');
    }
};
