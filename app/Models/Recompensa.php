<?php
// app/Models/Recompensa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'puntos_requeridos',
        'cantidad_disponible',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function canjes()
    {
        return $this->hasMany(CanjeRecompensa::class);
    }

    public function getDisponibleAttribute()
    {
        return $this->cantidad_disponible > 0 && $this->activa;
    }
}