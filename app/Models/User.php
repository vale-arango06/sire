<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Models\Grupo;
use App\Models\RegistroReciclaje;
use App\Models\CanjeRecompensa;

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

    // CORREGIDO: Permite acceso incluso si el rol es null o no está definido
    public function canAccessPanel(Panel $panel): bool
    {
        // Si es panel admin
        if ($panel->getId() === 'admin') {
            // Permitir si es admin O si no tiene rol definido (para usuarios creados con make:filament-user)
            return $this->rol === 'admin' || empty($this->rol) || is_null($this->rol);
        }

        // Si es panel estudiante
        if ($panel->getId() === 'estudiante') {
            return $this->rol === 'estudiante';
        }

        return false;
    }

    public function getRedirectPath(): ?string
    {
        if ($this->rol === 'admin' || empty($this->rol)) {
            return '/admin';
        }

        if ($this->rol === 'estudiante') {
            return '/estudiante';
        }

        return '/';
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin' || empty($this->rol);
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
        return $this->registrosReciclaje()->sum('puntos_ganados') ?? 0;
    }

    public function getPuntosEnPeriodo($fechaInicio, $fechaFin)
    {
        return $this->registrosReciclaje()
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->sum('puntos_ganados') ?? 0;
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
            ->sum('puntos_utilizados') ?? 0;
        
        return $totalPuntos - $puntosUtilizados;
    }

    public function canjes()
    {
        return $this->hasMany(CanjeRecompensa::class, 'usuario_id');
    }
}