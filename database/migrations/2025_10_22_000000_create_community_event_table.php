<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_event', function (Blueprint $table) {
            $table->id();
            $table->string('event_id');
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->unique(['event_id', 'community_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_event');
    }
};