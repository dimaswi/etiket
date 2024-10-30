<?php

namespace App\Filament\Resources\PermintaanResource\Pages;

use App\Filament\Resources\PermintaanResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePermintaan extends CreateRecord
{
    protected static string $resource = PermintaanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pemohon'] = auth()->id();

        return $data;
    }
}
