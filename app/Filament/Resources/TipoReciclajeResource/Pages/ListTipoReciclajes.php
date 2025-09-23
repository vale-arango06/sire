<?php

namespace App\Filament\Resources\TipoReciclajeResource\Pages;

use App\Filament\Resources\TipoReciclajeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoReciclajes extends ListRecords
{
    protected static string $resource = TipoReciclajeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
