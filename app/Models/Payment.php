<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'id_paiement';

    protected $fillable = [
        'id_facture',
        'date_paiement',
        'montant_paye',
        'mode_paiement',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant_paye' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'id_facture', 'id_facture');
    }
}
