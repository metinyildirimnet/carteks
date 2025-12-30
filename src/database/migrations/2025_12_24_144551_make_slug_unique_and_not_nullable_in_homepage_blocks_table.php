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
            // First, ensure all existing slugs are not null before changing the column
            // This should already be handled by the temporary route, but as a safeguard:
            \App\Models\HomepageBlock::whereNull('slug')->orWhere('slug', '')->get()->each(function ($block) {
                $block->slug = \Illuminate\Support\Str::slug($block->title) . '-' . $block->id; // Append id to guarantee uniqueness
                $block->save();
            });

            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_blocks', function (Blueprint $table) {
            $table->string('slug')->nullable()->dropUnique()->change();
        });
    }
};
