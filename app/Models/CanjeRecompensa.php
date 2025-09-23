<?php
// app/Models/CanjeRecompensa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanjeRecompensa extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'recompensa_id',
        'puntos_utilizados',
        'fecha_canje',
        'estado'
    ];

    protected $casts = [
        'fecha_canje' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function recompensa()
    {
        return $this->belongsTo(Recompensa::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($canje) {
            $canje->fecha_canje = now();
            
            // Verificar que el usuario tenga suficientes puntos
            $puntosDisponibles = $canje->usuario->getPuntosDisponibles();
            if ($puntosDisponibles < $canje->puntos_utilizados) {
                throw new \Exception('El usuario no tiene suficientes puntos disponibles');
            }

            // Verificar que la recompensa esté disponible
            if (!$canje->recompensa->disponible) {
                throw new \Exception('La recompensa no está disponible');
            }

            // Decrementar cantidad disponible
            $canje->recompensa->decrement('cantidad_disponible');
        });

        static::deleting(function ($canje) {
            // Si se cancela el canje, devolver la cantidad disponible
            if ($canje->estado !== 'entregada') {
                $canje->recompensa->increment('cantidad_disponible');
            }
        });
    }

    public function marcarComoEntregada()
    {
        $this->update(['estado' => 'entregada']);
    }

    public function cancelar()
    {
        $this->update(['estado' => 'cancelada']);
        $this->recompensa->increment('cantidad_disponible');
    }
}