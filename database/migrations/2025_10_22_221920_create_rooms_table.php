<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->string('name');
            $table->integer('capacity')->default(1);
            $table->enum('style', ['individual', 'group', 'conference'])->default('individual');
            $table->boolean('has_pc')->default(false);
            $table->boolean('has_wifi')->default(true);
            $table->json('equipments')->nullable();
            $table->decimal('price_per_hour', 8, 2)->default(0);
            $table->json('availability_schedule')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
