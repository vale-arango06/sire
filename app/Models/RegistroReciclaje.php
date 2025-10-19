<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'cantidad_kg',
        'puntos_ganados',
        'fecha',
        'unidad_medida',
    ];

    protected static function booted()
    {
        static::creating(function ($registro) {
            
            if (is_null($registro->usuario_id)) {
                $registro->usuario_id = Auth::id();
            }

            // Asignar cantidad igual a cantidad_kg automáticamente
            if (empty($registro->cantidad)) {
                $registro->cantidad = $registro->cantidad_kg;
            }

            // Asignar un tipo de reciclaje por defecto (el primero disponible)
            if (is_null($registro->tipo_id)) {
                $tipo = TipoReciclaje::first();
                if ($tipo) {
                    $registro->tipo_id = $tipo->id;
                }
            }

            // Calcular puntos usando los puntos REALES del material
            if (!empty($registro->cantidad_kg) && !empty($registro->material_id)) {
                $material = Material::find($registro->material_id);
                if ($material) {
                    $registro->puntos_ganados = $registro->cantidad_kg * $material->puntos;
                } else {
                    $registro->puntos_ganados = 0;
                }
            } else {
                $registro->puntos_ganados = 0;
            }
        });
    }

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoReciclaje::class, 'tipo_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
}