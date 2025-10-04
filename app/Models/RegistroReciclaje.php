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

    /**
     * Asignar usuario y calcular puntos automáticamente al crear un registro.
     */
    protected static function booted()
    {
        static::creating(function ($registro) {
            // CORREGIDO: Solo asignar Auth::id() si usuario_id es NULL (no viene del formulario)
            if (is_null($registro->usuario_id)) {
                $registro->usuario_id = Auth::id();
            }

            // Calcular puntos automáticamente
            if (!empty($registro->cantidad_kg)) {
                $registro->puntos_ganados = $registro->cantidad_kg * 2;
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