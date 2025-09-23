<?php
// app/Filament/Widgets/RankingGrupos.php
namespace App\Filament\Widgets;

use App\Models\Grupo;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RankingGrupos extends BaseWidget
{
    protected static ?string $heading = 'Ranking de Grupos';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Grupo::withCount('registrosReciclaje')
                    ->withSum('registrosReciclaje', 'puntos_ganados')
                    ->withSum('registrosReciclaje', 'cantidad_kg')
                    ->orderBy('registros_reciclaje_sum_puntos_ganados', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('#')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Grupo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grado.nombre')
                    ->label('Grado'),
                Tables\Columns\TextColumn::make('registros_reciclaje_sum_puntos_ganados')
                    ->label('Puntos')
                    ->numeric()
                    ->sortable()
                    ->default(0),
                Tables\Columns\TextColumn::make('registros_reciclaje_sum_cantidad_kg')
                    ->label('Total Reciclado (kg)')
                    ->numeric(decimalPlaces: 2)
                    ->default(0),
                Tables\Columns\TextColumn::make('registros_reciclaje_count')
                    ->label('Registros')
                    ->numeric()
                    ->default(0),
            ]);
    }
}