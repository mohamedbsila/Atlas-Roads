<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('borrow_request_id')->constrained('borrow_requests')->onDelete('cascade');
            $table->decimal('amount_total', 10, 2);
            $table->decimal('amount_per_day', 10, 4)->nullable();
            $table->string('currency', 5)->default('$');
            $table->string('status', 20)->default('pending'); // pending, paid, refunded
            $table->string('method', 30)->nullable(); // cash, card, etc
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['borrower_id', 'owner_id']);
            $table->index(['book_id', 'borrow_request_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
