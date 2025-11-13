<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'id_chambre';

    protected $fillable = [
        'numero_chambre',
        'type_chambre',
        'statut',
        'capacite_max',
    ];

    protected $casts = [
        'capacite_max' => 'integer',
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'type_chambre', 'id_type');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_chambre', 'id_chambre');
    }
}
