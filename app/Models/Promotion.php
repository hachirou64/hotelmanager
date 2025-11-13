<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';
    protected $primaryKey = 'id_promotion';

    protected $fillable = [
        'code_promotion',
        'pourcentage_reduction',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'pourcentage_reduction' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
}
