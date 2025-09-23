<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'grupo_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function registrosReciclaje()
    {
        return $this->hasMany(RegistroReciclaje::class, 'usuario_id');
    }

    public function getTotalPuntosAttribute()
    {
        return $this->registrosReciclaje()->sum('puntos_ganados');
    }

    // EXTRA: si quieres mantener tus métodos adicionales, no necesitas cambiar nada
    public function getPuntosEnPeriodo($fechaInicio, $fechaFin)
    {
        return $this->registrosReciclaje()
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('puntos_ganados');
    }

    public function getRankingPosition()
    {
        $usuarios = User::withSum('registrosReciclaje', 'puntos_ganados')
            ->orderBy('registros_reciclaje_sum_puntos_ganados', 'desc')
            ->pluck('id');
        
        return $usuarios->search($this->id) + 1;
    }

    public function getCanjesPendientes()
    {
        return $this->canjes()
            ->where('estado', 'pendiente')
            ->count();
    }

    public function getPuntosDisponibles()
    {
        $totalPuntos = $this->getTotalPuntosAttribute();
        $puntosUtilizados = $this->canjes()
            ->where('estado', '!=', 'cancelada')
            ->sum('puntos_utilizados');
        
        return $totalPuntos - $puntosUtilizados;
    }

    public function canjes()
    {
        return $this->hasMany(CanjeRecompensa::class, 'usuario_id');
    }
}
