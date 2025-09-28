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

                TextInput::make('unidad_medida')
                    ->label('Unidad de Medida')
                    ->maxLength(20)
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
                TextColumn::make('unidad_medida')->label('Unidad'),
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('Y-m-d'), // 👈 solo año-mes-día
                TextColumn::make('created_at')
                    ->label('Registrado en')
                    ->date('Y-m-d'), // 👈 solo año-mes-día
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistroReciclajes::route('/'),
        ];
    }
}
