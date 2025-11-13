<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $table = 'room_types';
    protected $primaryKey = 'id_type';

    protected $fillable = [
        'nom_type',
        'description',
        'prix_base',
    ];

    protected $casts = [
        'prix_base' => 'decimal:2',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'type_chambre', 'id_type');
    }
}
