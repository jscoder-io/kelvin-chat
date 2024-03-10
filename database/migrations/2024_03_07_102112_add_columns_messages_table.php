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
        Schema::table('messages', function (Blueprint $table) {
            $table->string('order_customer')->nullable()->after('order_data');
            $table->string('order_contact')->nullable()->after('order_data');
            $table->text('order_address')->nullable()->after('order_data');
            $table->json('order_total')->nullable()->after('order_data');
            $table->json('order_detail')->nullable()->after('order_data');
            $table->string('order_id')->nullable()->after('order_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('order_customer');
            $table->dropColumn('order_contact');
            $table->dropColumn('order_address');
            $table->dropColumn('order_total');
            $table->dropColumn('order_detail');
            $table->dropColumn('order_id');
        });
    }
};
