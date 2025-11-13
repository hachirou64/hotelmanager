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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id('id_chambre');
            $table->string('numero_chambre')->unique();
            $table->foreignId('type_chambre')->constrained('room_types', 'id_type');
            $table->enum('statut', ['libre', 'occupée', 'nettoyage', 'maintenance'])->default('libre');
            $table->integer('capacite_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
