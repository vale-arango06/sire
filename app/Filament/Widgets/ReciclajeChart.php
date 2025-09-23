<?php
// app/Filament/Widgets/ReciclajeChart.php
namespace App\Filament\Widgets;

use App\Models\RegistroReciclaje;
use Filament\Widgets\ChartWidget;

class ReciclajeChart extends ChartWidget
{
    protected static ?string $heading = 'Reciclaje por Material';

    protected function getData(): array
    {
        $data = RegistroReciclaje::with('material')
            ->selectRaw('material_id, SUM(cantidad_kg) as total')
            ->groupBy('material_id')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad (kg)',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40'
                    ],
                ],
            ],
            'labels' => $data->map(fn($item) => $item->material->nombre)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}