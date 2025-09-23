<?php
// app/Models/Grupo.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['grado_id', 'nombre'];

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function registrosReciclaje()
    {
        return $this->hasMany(RegistroReciclaje::class);
    }

    public function getTotalPuntosAttribute()
    {
        return $this->registrosReciclaje()->sum('puntos_ganados');
    }

    // MÉTODOS ADICIONALES
    public function getPuntosPromedioPorEstudiante()
    {
        $totalPuntos = $this->registrosReciclaje()->sum('puntos_ganados');
        $totalEstudiantes = $this->usuarios()->count();
        
        return $totalEstudiantes > 0 ? $totalPuntos / $totalEstudiantes : 0;
    }

    public function getMaterialMasReciclado()
    {
        return $this->registrosReciclaje()
            ->with('material')
            ->selectRaw('material_id, SUM(cantidad_kg) as total')
            ->groupBy('material_id')
            ->orderBy('total', 'desc')
            ->first();
    }

    public function getTotalKgRecicladosAttribute()
    {
        return $this->registrosReciclaje()->sum('cantidad_kg');
    }

    public function getRankingPosition()
    {
        $grupos = Grupo::withSum('registrosReciclaje', 'puntos_ganados')
            ->orderBy('registros_reciclaje_sum_puntos_ganados', 'desc')
            ->pluck('id');
        
        return $grupos->search($this->id) + 1;
    }

    public function getPuntosEnMes($mes, $año = null)
    {
        $año = $año ?? date('Y');
        
        return $this->registrosReciclaje()
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $año)
            ->sum('puntos_ganados');
    }

    public function getEstadisticasPorMaterial()
    {
        return $this->registrosReciclaje()
            ->with('material')
            ->selectRaw('material_id, SUM(cantidad_kg) as total_kg, SUM(puntos_ganados) as total_puntos, COUNT(*) as total_registros')
            ->groupBy('material_id')
            ->orderBy('total_puntos', 'desc')
            ->get();
    }

    public function getEstudianteMasActivo()
    {
        return $this->usuarios()
            ->withSum('registrosReciclaje', 'puntos_ganados')
            ->orderBy('registros_reciclaje_sum_puntos_ganados', 'desc')
            ->first();
    }

    public function getNombreCompletoAttribute()
    {
        return $this->grado->nombre . ' - ' . $this->nombre;
    }
}