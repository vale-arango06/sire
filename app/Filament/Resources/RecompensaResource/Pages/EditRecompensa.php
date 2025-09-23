<?php

namespace App\Filament\Resources\RecompensaResource\Pages;

use App\Filament\Resources\RecompensaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecompensa extends EditRecord
{
    protected static string $resource = RecompensaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
