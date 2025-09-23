<?php

namespace App\Filament\Resources\RegistroReciclajeResource\Pages;

use App\Filament\Resources\RegistroReciclajeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistroReciclaje extends EditRecord
{
    protected static string $resource = RegistroReciclajeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
