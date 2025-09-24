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
        'rol',
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

    // Controla acceso según el rol y el panel
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->rol === 'admin' && $panel->getId() === 'admin') {
            return true;
        }

        if ($this->rol === 'estudiante' && $panel->getId() === 'estudiante') {
            return true;
        }

        return false;
    }

    public function getRedirectPath(): ?string
    {
        if ($this->rol === 'admin') {
            return '/admin'; // dashboard del panel admin
        }

        if ($this->rol === 'estudiante') {
            return '/estudiante'; // dashboard del panel estudiante
        }

        return '/'; // fallback
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
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