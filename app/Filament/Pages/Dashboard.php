<?php
// app/Filament/Pages/Dashboard.php
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard - Sistema de Reciclaje';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\EstadisticasGenerales::class,
            \App\Filament\Widgets\ReciclajeChart::class,
            \App\Filament\Widgets\RankingGrupos::class,
        ];
    }
}