<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'id_facture';

    protected $fillable = [
        'id_reservation',
        'id_client',
        'date_facture',
        'montant_total',
        'statut_paiement',
        'export_format',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'montant_total' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_facture', 'id_facture');
    }
}
