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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['user_id']);
            // Then change the column to nullable
            $table->foreignId('user_id')->nullable()->change();
            // Re-add the foreign key constraint with nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // To revert, first drop the nullable foreign key
            $table->dropForeign(['user_id']);
            // Then change the column back to not nullable
            $table->foreignId('user_id')->change();
            // Re-add the foreign key constraint as not nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
