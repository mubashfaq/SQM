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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('permalink')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('featured_images')->nullable(); 
            $table->string('fimage_alt_text')->nullable(); 
            
            // SEO fields
            $table->string('_seo_title')->nullable();
            $table->string('_seo_tags')->nullable();
            $table->longText('_seo_description')->nullable();
            $table->string('_canonical_url')->nullable();
            $table->text('_schema_markup')->nullable();
            $table->tinyInteger('_page_index')->nullable();
            
            // Status and user tracking
            $table->tinyInteger('active')->default(0); // 0=draft, 1=public, 2=private, 3=trash
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // for trash functionality
        });

        // Pivot table for blog categories
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('blog_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->timestamps();
            
        });

        // Pivot table for blog child categories
        Schema::create('blog_child_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('blog_id')->nullable();
            $table->integer('child_category_id')->nullable();
            $table->timestamps();
            
        });

        // Pivot table for blog grand child categories
        Schema::create('blog_grand_child_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('blog_id')->nullable();
            $table->integer('grand_child_category_id')->nullable();
            $table->timestamps();
            
        });

        // Pivot table for blog brands
        Schema::create('blog_brands', function (Blueprint $table) {
            $table->id();
            $table->integer('blog_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_brands');
        Schema::dropIfExists('blog_grand_child_categories');
        Schema::dropIfExists('blog_child_categories');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blogs');
    }
};
