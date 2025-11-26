<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('mode_paiement');
            $table->string('provider')->nullable()->after('transaction_id');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending')->after('provider');
            $table->json('metadata')->nullable()->after('status');
        });

        // Alter enum to add MOMO to mode_paiement. Using raw SQL to be portable with existing enum.
        // This statement keeps existing values and appends 'MOMO'. Adjust if your DB has different values.
        // Get the current enum definition would require querying information_schema; here we assume the
        // original enum values are exactly: 'CB','espèces','virement'. If your DB differs, edit this migration.
        DB::statement("ALTER TABLE payments MODIFY mode_paiement ENUM('CB','espèces','virement','MOMO')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'provider', 'status', 'metadata']);
        });

        // Note: reversing the enum change is non-trivial without knowing original state; skipping.
    }
};
