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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id('id_reservation');
            $table->foreignId('id_client')->constrained('clients', 'id_client');
            $table->foreignId('id_chambre')->constrained('rooms', 'id_chambre');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('statut', ['confirmée', 'en cours', 'annulée'])->default('confirmée');
            $table->text('demandes_speciales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
