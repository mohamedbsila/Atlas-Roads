<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
=======
>>>>>>> origin/draft

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title', 255);
            $table->string('author', 100);
            $table->string('isbn', 20)->nullable()->unique();
            $table->string('category', 50);
            $table->string('language', 30);
<<<<<<< HEAD
            $table->unsignedSmallInteger('published_year');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            // Index basiques sans problème de longueur
            $table->index('category');
            $table->index('is_available');
            $table->index('published_year');
=======
            $table->year('published_year');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->index(['title', 'author']);
            $table->index('category');
            $table->index('is_available');
>>>>>>> origin/draft
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
