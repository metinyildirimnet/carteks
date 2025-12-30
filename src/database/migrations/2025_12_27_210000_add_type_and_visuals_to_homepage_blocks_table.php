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
            $table->string('type')->default('product')->after('description');
            $table->string('image_path')->nullable()->after('type');
            $table->boolean('show_on_desktop')->default(true)->after('is_active');
            $table->boolean('show_on_mobile')->default(true)->after('show_on_desktop');
            
            // For clickable link on visual blocks
            $table->string('link_url')->nullable()->after('image_path');
            $table->nullableMorphs('linkable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_blocks', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'image_path',
                'show_on_desktop',
                'show_on_mobile',
                'link_url',
                'linkable_id',
                'linkable_type'
            ]);
        });
    }
};
