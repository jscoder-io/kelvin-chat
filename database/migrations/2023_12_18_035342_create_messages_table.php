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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_id');
            $table->integer('buyer_id');
            $table->unsignedBigInteger('shop_id');
            $table->string('username')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('product_title')->nullable();
            $table->string('product_image')->nullable();
            $table->string('channel_url')->nullable();
            $table->text('latest_message')->nullable();
            $table->unsignedTinyInteger('unread_count')->default(0);
            $table->json('data')->nullable();
            $table->timestamp('latest_created')->nullable();

            $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
