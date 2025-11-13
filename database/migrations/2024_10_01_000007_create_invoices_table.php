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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('id_facture');
            $table->foreignId('id_reservation')->constrained('reservations', 'id_reservation');
            $table->foreignId('id_client')->constrained('clients', 'id_client');
            $table->date('date_facture');
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut_paiement', ['payée', 'impayée'])->default('impayée');
            $table->enum('export_format', ['PDF', 'Excel'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
