<?php

namespace App\Filament\Resources\KotakSaranResource\Pages;

use App\Filament\Resources\KotakSaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKotakSaran extends EditRecord
{
    protected static string $resource = KotakSaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
