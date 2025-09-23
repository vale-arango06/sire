<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroReciclaje extends Model
{
    use HasFactory;

    protected $table = 'registros_reciclaje';

    protected $fillable = [
        'usuario_id',
        'material_id',
        'tipo_id',
        'grupo_id',
        'cantidad',
        'fecha',
        'cantidad_kg',
        'puntos_ganados'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'cantidad' => 'decimal:2',
        'cantidad_kg' => 'decimal:2',
    ];

    // Evento que se ejecuta antes de crear el registro
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->puntos_ganados = $model->calcularPuntos();
        });

        static::updating(function ($model) {
            $model->puntos_ganados = $model->calcularPuntos();
        });
    }

    // Método para calcular puntos basado en el material
    private function calcularPuntos()
    {
        // Si no hay material_id o cantidad_kg, retornar 0
        if (!$this->material_id || !$this->cantidad_kg) {
            return 0;
        }

        // Obtener el material con su valor en puntos
        $material = Material::find($this->material_id);
        
        if (!$material) {
            return 0;
        }

        // Calcular puntos: cantidad_kg * puntos_por_kg del material
        // Asumiendo que el modelo Material tiene un campo 'puntos_por_kg' o 'valor_puntos'
        $puntosPorKg = $material->puntos_por_kg ?? $material->valor_puntos ?? 1;
        
        return (int) ($this->cantidad_kg * $puntosPorKg);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function tipoReciclaje()
    {
        return $this->belongsTo(TipoReciclaje::class, 'tipo_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}