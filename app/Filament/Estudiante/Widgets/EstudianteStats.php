<?php
namespace App\Filament\Estudiante\Widgets;

use App\Models\RegistroReciclaje;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class EstudianteStats extends BaseWidget
{
    protected function getStats(): array
    {
        $usuario = Auth::user();
        
        if (!$usuario) {
            return [];
        }

        
        $puntosTotal = RegistroReciclaje::where('usuario_id', $usuario->id)->sum('puntos_ganados');
        $registrosMes = RegistroReciclaje::where('usuario_id', $usuario->id)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();
        $totalMateriales = RegistroReciclaje::where('usuario_id', $usuario->id)->sum('cantidad_kg');


        return [
            Stat::make('Mis Puntos Totales', $puntosTotal ?: 0)
                ->description('Puntos acumulados')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            
            Stat::make('Registros Este Mes', $registrosMes ?: 0)
                ->description('Actividad del mes actual')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
            
            Stat::make('Total Reciclado', ($totalMateriales ?: 0) . ' kg')
                ->description('Cantidad total reciclada')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
        ];
    }
}