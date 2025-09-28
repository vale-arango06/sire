<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistroReciclajeResource\Pages;
use App\Models\RegistroReciclaje;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistroReciclajeResource extends Resource
{
    protected static ?string $model = RegistroReciclaje::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationLabel = 'Registros Reciclaje';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('usuario_id')
                    ->relationship('usuario', 'name')
                    ->required(),

                Forms\Components\Select::make('material_id')
                    ->relationship('material', 'nombre')
                    ->required(),

                Forms\Components\Select::make('tipo_id')
                    ->relationship('tipo', 'nombre')
                    ->required(),

                Forms\Components\Select::make('grupo_id')
                    ->relationship('grupo', 'nombre')
                    ->required(),

                Forms\Components\TextInput::make('cantidad')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('cantidad_kg')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('puntos')
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('fecha')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usuario.name')->label('Usuario'),
                Tables\Columns\TextColumn::make('material.nombre')->label('Material'),
                Tables\Columns\TextColumn::make('tipo.nombre')->label('Tipo'),
                Tables\Columns\TextColumn::make('grupo.nombre')->label('Grupo'),
                Tables\Columns\TextColumn::make('cantidad')->label('Cantidad'),
                Tables\Columns\TextColumn::make('cantidad_kg')->label('Cantidad KG'),
                Tables\Columns\TextColumn::make('puntos')->label('Puntos'),
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('Y-m-d'), // 👈 solo año-mes-día
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado en')
                    ->date('Y-m-d'), // 👈 solo año-mes-día
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistroReciclajes::route('/'),
            'create' => Pages\CreateRegistroReciclaje::route('/create'),
            'edit' => Pages\EditRegistroReciclaje::route('/{record}/edit'),
        ];
    }
}
