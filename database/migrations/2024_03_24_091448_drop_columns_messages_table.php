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
            $table->dropColumn('order_data');
            $table->dropColumn('order_id');
            $table->dropColumn('order_detail');
            $table->dropColumn('order_total');
            $table->dropColumn('order_address');
            $table->dropColumn('order_contact');
            $table->dropColumn('order_customer');
            $table->dropColumn('is_cancelled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
};
