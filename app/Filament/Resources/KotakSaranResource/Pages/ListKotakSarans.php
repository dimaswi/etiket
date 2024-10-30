<?php

namespace App\Filament\Resources\KotakSaranResource\Pages;

use App\Filament\Resources\KotakSaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKotakSarans extends ListRecords
{
    protected static string $resource = KotakSaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
