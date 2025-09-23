<?php
// app/Filament/Resources/MaterialResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('unidad_medida')
                    ->options([
                        'kg' => 'Kilogramos',
                        'g' => 'Gramos',
                        'unidad' => 'Unidades',
                        'litros' => 'Litros',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('puntos')
                    ->required()
                    ->numeric()
                    ->minValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unidad_medida')
                    ->label('Unidad de Medida'),
                Tables\Columns\TextColumn::make('puntos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registros_reciclaje_count')
                    ->counts('registrosReciclaje')
                    ->label('Registros'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unidad_medida')
                    ->options([
                        'kg' => 'Kilogramos',
                        'g' => 'Gramos',
                        'unidad' => 'Unidades',
                        'litros' => 'Litros',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}