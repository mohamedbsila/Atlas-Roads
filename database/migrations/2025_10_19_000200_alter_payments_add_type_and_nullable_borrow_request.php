<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add column 'type' with default 'borrow'
        Schema::table('payments', function (Blueprint $table) {
            $table->string('type', 20)->default('borrow')->after('status');
        });

        // Make borrow_request_id nullable (MySQL specific raw SQL to avoid requiring doctrine/dbal)
        // Drop existing foreign key if exists, then modify column and re-add FK
        try {
            DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_borrow_request_id_foreign');
        } catch (\Throwable $e) {
            // ignore if FK name differs or doesn't exist (e.g., on sqlite during tests)
        }
        try {
            DB::statement('ALTER TABLE payments MODIFY borrow_request_id BIGINT UNSIGNED NULL');
        } catch (\Throwable $e) {
            // Fallback for SQLite or other drivers which may not support the above
        }
        try {
            DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_borrow_request_id_foreign FOREIGN KEY (borrow_request_id) REFERENCES borrow_requests(id) ON DELETE CASCADE');
        } catch (\Throwable $e) {
            // ignore on drivers without FK or if already exists
        }
    }

    public function down(): void
    {
        // Revert borrow_request_id to NOT NULL if possible
        try {
            DB::statement('ALTER TABLE payments DROP FOREIGN KEY payments_borrow_request_id_foreign');
        } catch (\Throwable $e) {}
        try {
            DB::statement('ALTER TABLE payments MODIFY borrow_request_id BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {}
        try {
            DB::statement('ALTER TABLE payments ADD CONSTRAINT payments_borrow_request_id_foreign FOREIGN KEY (borrow_request_id) REFERENCES borrow_requests(id) ON DELETE CASCADE');
        } catch (\Throwable $e) {}

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
