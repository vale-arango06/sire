<?php
// app/Filament/Resources/RecompensaResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\RecompensaResource\Pages;
use App\Models\Recompensa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RecompensaResource extends Resource
{
    protected static ?string $model = Recompensa::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Recompensas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('descripcion')
                    ->required()
                    ->rows(3),
                Forms\Components\TextInput::make('puntos_requeridos')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('cantidad_disponible')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Toggle::make('activa')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('puntos_requeridos')
                    ->label('Puntos Requeridos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad_disponible')
                    ->label('Disponible')
                    ->numeric(),
                Tables\Columns\BooleanColumn::make('activa')
                    ->label('Activa'),
                Tables\Columns\TextColumn::make('canjes_count')
                    ->counts('canjes')
                    ->label('Canjes'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activa'),
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
            'index' => Pages\ListRecompensas::route('/'),
            'create' => Pages\CreateRecompensa::route('/create'),
            'edit' => Pages\EditRecompensa::route('/{record}/edit'),
        ];
    }
}