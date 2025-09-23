<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReciclaje extends Model
{
    use HasFactory;

    protected $table = 'tipo_reciclaje';

    protected $fillable = ['nombre'];

    public function registrosReciclaje()
    {
        return $this->hasMany(RegistroReciclaje::class, 'tipo_id');
    }
}