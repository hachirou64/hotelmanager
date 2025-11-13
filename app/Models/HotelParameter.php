<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelParameter extends Model
{
    use HasFactory;

    protected $table = 'hotel_parameters';
    protected $primaryKey = 'id_parametre';

    protected $fillable = [
        'nom',
        'valeur',
        'description',
    ];
}
