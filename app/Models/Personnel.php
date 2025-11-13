<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personnel extends Model
{
    use HasFactory;

    protected $table = 'personnel';
    protected $primaryKey = 'id_employe';

    protected $fillable = [
        'id_utilisateur',
        'nom',
        'prenom',
        'role',
        'service',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}
