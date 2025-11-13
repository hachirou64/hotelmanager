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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_paiement');
            $table->foreignId('id_facture')->constrained('invoices', 'id_facture');
            $table->date('date_paiement');
            $table->decimal('montant_paye', 10, 2);
            $table->enum('mode_paiement', ['CB', 'espèces', 'virement']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
