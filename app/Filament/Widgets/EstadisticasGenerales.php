<?php
// app/Filament/Widgets/EstadisticasGenerales.php
namespace App\Filament\Widgets;

use App\Models\RegistroReciclaje;
use App\Models\User;
use App\Models\Material;
use App\Models\Grupo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EstadisticasGenerales extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Estudiantes', User::count())
                ->description('Usuarios registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            
            Stat::make('Total Materiales Reciclados', RegistroReciclaje::sum('cantidad_kg') . ' kg')
                ->description('Cantidad total reciclada')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            
            Stat::make('Puntos Totales', RegistroReciclaje::sum('puntos_ganados'))
                ->description('Puntos acumulados')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            
            Stat::make('Registros Este Mes', RegistroReciclaje::whereMonth('fecha', now()->month)->count())
                ->description('Actividad del mes actual')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),
        ];
    }
}