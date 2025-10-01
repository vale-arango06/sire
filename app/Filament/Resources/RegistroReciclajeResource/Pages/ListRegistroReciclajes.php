<?php

namespace App\Filament\Resources\RegistroReciclajeResource\Pages;

use App\Filament\Resources\RegistroReciclajeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistroReciclajes extends ListRecords
{
    protected static string $resource = RegistroReciclajeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('➕ Nuevo Registro'),
        ];
    }
}
