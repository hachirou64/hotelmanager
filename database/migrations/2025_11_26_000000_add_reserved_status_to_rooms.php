<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'réservée' to the enum values for rooms.statut
        // This uses raw SQL because Laravel Schema doesn't support altering enum values directly.
        DB::statement("ALTER TABLE `rooms` MODIFY `statut` ENUM('libre','occupée','nettoyage','maintenance','réservée') NOT NULL DEFAULT 'libre';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum without 'réservée'
        DB::statement("ALTER TABLE `rooms` MODIFY `statut` ENUM('libre','occupée','nettoyage','maintenance') NOT NULL DEFAULT 'libre';");
    }
};
