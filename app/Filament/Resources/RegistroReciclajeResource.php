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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Registros Reciclaje';
    protected static ?string $navigationGroup = 'Reciclaje';
    protected static ?string $pluralModelLabel = 'Registros de Reciclaje';
    protected static ?string $modelLabel = 'Registro de Reciclaje';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('usuario_id')
                    ->label('Estudiante')
                    ->relationship('usuario', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $usuario = \App\Models\User::find($state);
                            if ($usuario && $usuario->grupo_id) {
                                $set('grupo_id', $usuario->grupo_id);
                            }
                        }
                    }),

                Forms\Components\Select::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('tipo_id')
                    ->label('Tipo de Reciclaje')
                    ->relationship('tipo', 'nombre')
                    ->required(),

                Forms\Components\Select::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('cantidad')
                    ->label('Cantidad')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->required(),

                Forms\Components\TextInput::make('cantidad_kg')
                    ->label('Cantidad en KG')
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->required()
                    ->helperText('Los puntos se calcularán automáticamente'),

                Forms\Components\DateTimePicker::make('fecha')
                    ->label('Fecha y Hora')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('material.nombre')
                    ->label('Material')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tipo.nombre')
                    ->label('Tipo')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->suffix(fn ($record) => ' ' . ($record->material->unidad_medida ?? '')),

                Tables\Columns\TextColumn::make('cantidad_kg')
                    ->label('Peso (kg)')
                    ->numeric(decimalPlaces: 2)
                    ->suffix(' kg')
                    ->sortable(),

                Tables\Columns\TextColumn::make('puntos_ganados')
                    ->label('Puntos')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('usuario_id')
                    ->label('Estudiante')
                    ->relationship('usuario', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'nombre'),

                Tables\Filters\SelectFilter::make('grupo_id')
                    ->label('Grupo')
                    ->relationship('grupo', 'nombre'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('fecha', 'desc');
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