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
        Schema::table('homepage_blocks', function (Blueprint $table) {
            $table->dropUnique('homepage_blocks_sort_order_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_blocks', function (Blueprint $table) {
            $table->unique('sort_order', 'homepage_blocks_sort_order_unique');
        });
    }
};
