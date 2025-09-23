<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}