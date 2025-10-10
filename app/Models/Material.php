<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materiales'; // 

    protected $fillable = ['nombre', 'unidad_medida', 'puntos'];

    public function registrosReciclaje()
    {
        return $this->hasMany(RegistroReciclaje::class);
    }
}
