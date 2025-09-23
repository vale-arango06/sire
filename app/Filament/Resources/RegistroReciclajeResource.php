<?php
// app/Filament/Resources/RegistroReciclajeResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\RegistroReciclajeResource\Pages;
use App\Models\RegistroReciclaje;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RegistroReciclajeResource extends Resource
{
    protected static ?string $model = RegistroReciclaje::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Reciclaje';
    protected static ?string $label = 'Registro de Reciclaje';
    protected static ?string $pluralLabel = 'Registros de Reciclaje';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('usuario_id')
                    ->relationship('usuario', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('material_id')
                    ->relationship('material', 'nombre')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $material = \App\Models\Material::find($state);
                            if ($material && $material->grupo_id) {
                                $set('grupo_id', $material->grupo_id);
                            }
                        }
                    }),
                Forms\Components\Select::make('tipo_id')
                    ->relationship('tipoReciclaje', 'nombre')
                    ->required(),
                Forms\Components\Select::make('grupo_id')
                    ->relationship('grupo', 'nombre')
                    ->required(),
                Forms\Components\TextInput::make('cantidad')
                    ->required()
                    ->numeric()
                    ->step(0.01),
                Forms\Components\DateTimePicker::make('fecha')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('cantidad_kg')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->label('Cantidad en KG'),
                Forms\Components\TextInput::make('puntos_ganados')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Puntos (se calcula automáticamente)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('material.nombre')
                    ->label('Material')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipoReciclaje.nombre')
                    ->label('Tipo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('grupo.nombre')
                    ->label('Grupo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad_kg')
                    ->label('Cantidad KG')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('puntos_ganados')
                    ->label('Puntos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('material')
                    ->relationship('material', 'nombre'),
                Tables\Filters\SelectFilter::make('tipo')
                    ->relationship('tipoReciclaje', 'nombre'),
                Tables\Filters\SelectFilter::make('grupo')
                    ->relationship('grupo', 'nombre'),
                Tables\Filters\Filter::make('fecha')
                    ->form([
                        Forms\Components\DatePicker::make('desde')->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['desde'], fn ($q, $date) => $q->whereDate('fecha', '>=', $date))
                            ->when($data['hasta'], fn ($q, $date) => $q->whereDate('fecha', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['desde'] ?? null) {
                            $indicators[] = 'Desde: ' . $data['desde'];
                        }
                        if ($data['hasta'] ?? null) {
                            $indicators[] = 'Hasta: ' . $data['hasta'];
                        }
                        return $indicators;
                    })
                    ->label('Rango de fechas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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