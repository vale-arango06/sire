<?php

namespace App\Filament\Estudiante\Resources;

use App\Filament\Estudiante\Resources\RegistroReciclajeResource\Pages;
use App\Models\RegistroReciclaje;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class RegistroReciclajeResource extends Resource
{
    protected static ?string $model = RegistroReciclaje::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationLabel = 'Mis Registros';
    protected static ?string $pluralModelLabel = 'Mis Registros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'nombre')
                    ->required(),

                TextInput::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->required(),

                DatePicker::make('fecha')
                    ->label('Fecha del Registro')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('material.nombre')->label('Material'),
                TextColumn::make('cantidad')->label('Cantidad'),
                TextColumn::make('fecha')->label('Fecha'),
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistroReciclajes::route('/'),
            // 'create' => Pages\CreateRegistroReciclaje::route('/create'),
            // 'edit' => Pages\EditRegistroReciclaje::route('/{record}/edit'),
        ];
    }
}
