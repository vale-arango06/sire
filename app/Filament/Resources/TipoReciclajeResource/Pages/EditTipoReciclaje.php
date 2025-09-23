<?php

namespace App\Filament\Resources\TipoReciclajeResource\Pages;

use App\Filament\Resources\TipoReciclajeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoReciclaje extends EditRecord
{
    protected static string $resource = TipoReciclajeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
