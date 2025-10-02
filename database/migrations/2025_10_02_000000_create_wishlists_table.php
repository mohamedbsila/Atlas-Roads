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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('isbn')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'found', 'ordered', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->foreignId('book_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_found')->default(false);
            $table->timestamp('found_at')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->integer('estimated_days')->nullable();
            $table->decimal('max_price', 10, 2)->nullable();
            $table->integer('service_rating')->nullable();
            $table->timestamps();
        });

        // Create pivot table for wishlist votes
        Schema::create('wishlist_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wishlist_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'wishlist_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_votes');
        Schema::dropIfExists('wishlists');
    }
}; 