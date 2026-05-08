<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id_client';

    protected $fillable = [
        'nom',
        'prenom',
        'adresse_email',
        'telephone',
        'historique_sejours',
        'preferences',
        'user_id',
    ];

    protected $casts = [
        'historique_sejours' => 'array',
        'preferences' => 'array',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_client', 'id_client');
    }

public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_client', 'id_client');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
