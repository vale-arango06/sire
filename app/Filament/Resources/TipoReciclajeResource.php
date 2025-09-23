<?php
// app/Filament/Resources/TipoReciclajeResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\TipoReciclajeResource\Pages;
use App\Models\TipoReciclaje;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TipoReciclajeResource extends Resource
{
    protected static ?string $model = TipoReciclaje::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Configuración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registros_reciclaje_count')
                    ->counts('registrosReciclaje')
                    ->label('Registros'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTipoReciclajes::route('/'),
            'create' => Pages\CreateTipoReciclaje::route('/create'),
            'edit' => Pages\EditTipoReciclaje::route('/{record}/edit'),
        ];
    }
}