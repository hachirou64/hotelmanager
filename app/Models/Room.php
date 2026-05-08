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
        'image',
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

    /**
     * Return a usable URL for the room image. Falls back to a source.unsplash image.
     */
    public function getImageUrlAttribute()
    {
        // prefer explicitly set image
        if ($this->image) {
            // if stored path (relative), use asset(), otherwise return as is
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            return asset($this->image);
        }

        // fallback dynamic image from Unsplash
        $id = $this->id_chambre ?? $this->id ?? rand(1, 1000);
        return "https://source.unsplash.com/collection/190727/800x600?sig={$id}";
    }
}
