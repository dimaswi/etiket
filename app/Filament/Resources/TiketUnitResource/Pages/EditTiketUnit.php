<?php

namespace App\Filament\Resources\TiketUnitResource\Pages;

use App\Filament\Resources\TiketUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTiketUnit extends EditRecord
{
    protected static string $resource = TiketUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
