<?php

namespace App\Filament\Resources\TiketUnitResource\Pages;

use App\Filament\Resources\TiketUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTiketUnits extends ListRecords
{
    protected static string $resource = TiketUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
