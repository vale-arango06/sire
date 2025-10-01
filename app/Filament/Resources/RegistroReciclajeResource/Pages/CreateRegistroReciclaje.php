<?php

namespace App\Filament\Resources\RegistroReciclajeResource\Pages;

use App\Filament\Resources\RegistroReciclajeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRegistroReciclaje extends CreateRecord
{
    protected static string $resource = RegistroReciclajeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
