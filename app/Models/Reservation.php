<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'id_reservation';

    protected $fillable = [
        'id_client',
        'id_chambre',
        'date_debut',
        'date_fin',
        'statut',
        'demandes_speciales',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client', 'id_client');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'id_chambre', 'id_chambre');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id_reservation', 'id_reservation');
    }
}
