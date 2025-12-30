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
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('desktop_image_path');
            $table->string('mobile_image_path');
            $table->text('content')->nullable();
            $table->string('button_text')->nullable();
            
            // For button link
            $table->string('button_url')->nullable(); // For custom links
            $table->nullableMorphs('linkable'); // For Page, Product, Category etc.

            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
