<?php

namespace App\Filament\Estudiante\Resources\RegistroReciclajeResource\Pages;

use App\Filament\Estudiante\Resources\RegistroReciclajeResource;
use Filament\Resources\Pages\ListRecords;

class ListRegistroReciclajes extends ListRecords
{
    protected static string $resource = RegistroReciclajeResource::class;

    protected function getHeaderActions(): array
    {
        return []; // quita el botón "New Registro de Reciclaje"
    }
}
