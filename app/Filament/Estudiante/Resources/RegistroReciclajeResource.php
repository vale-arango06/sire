<?php

namespace App\Filament\Estudiante\Resources;

use App\Filament\Estudiante\Resources\RegistroReciclajeResource\Pages;
use App\Models\RegistroReciclaje;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RegistroReciclajeResource extends Resource
{
    protected static ?string $model = RegistroReciclaje::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Mis Registros';
    protected static ?string $pluralModelLabel = 'Registros de Reciclaje';
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('usuario_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('material.nombre')
                    ->label('Material')
                    ->searchable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('tipo.nombre')
                    ->label('Tipo')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->formatStateUsing(fn ($state) => number_format($state, 0))
                    ->suffix(fn ($record) => ' ' . ($record->material->unidad_medida ?? '')),

                Tables\Columns\TextColumn::make('cantidad_kg')
                    ->label('Peso')
                    ->formatStateUsing(fn ($state) => number_format($state, 0))
                    ->suffix(' kg')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('puntos_ganados')
                    ->label('Puntos')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->size('lg'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'nombre')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('tipo_id')
                    ->label('Tipo')
                    ->relationship('tipo', 'nombre'),

                Tables\Filters\Filter::make('este_mes')
                    ->label('Este mes')
                    ->query(fn ($query) => $query->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)),

                Tables\Filters\Filter::make('esta_semana')
                    ->label('Esta semana')
                    ->query(fn ($query) => $query->whereBetween('fecha', [now()->startOfWeek(), now()->endOfWeek()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver'),
            ])
            ->bulkActions([])
            ->defaultSort('fecha', 'desc')
            ->emptyStateHeading('No tienes registros aún')
            ->emptyStateDescription('Cuando el administrador registre tu reciclaje, aparecerá aquí.')
            ->emptyStateIcon('heroicon-o-clipboard-document');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return $record->usuario_id === Auth::id();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistroReciclajes::route('/'),
            'view' => Pages\ViewRegistroReciclaje::route('/{record}'),
        ];
    }
}
