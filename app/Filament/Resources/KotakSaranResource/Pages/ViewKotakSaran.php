<?php

namespace App\Filament\Resources\KotakSaranResource\Pages;

use App\Filament\Resources\KotakSaranResource;
use App\Models\KotakSaran;
use App\Models\Tiket;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewKotakSaran extends ViewRecord
{
    protected static string $resource = KotakSaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            Action::make('Tindak_lanjut')
                ->label('Tindak Lanjut')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->form([
                    Select::make('worker')
                        ->label('Worker')
                        ->options(User::query()->pluck('name', 'id'))
                        ->required(),
                    Textarea::make('masukan')
                        ->label('Masukan')
                        ->required(),
                ])
                ->action(function (array $data, KotakSaran $record) {
                    Tiket::create([
                        'kotak_sarans_id' => $record->id,
                        'masukan' => $data['masukan'],
                        'worker' => $data['worker'],
                    ]);

                    $record->update(['status' => 1]);

                    Notification::make()
                        ->title('Tiket sudah dikirim ke worker!')
                        ->success()
                        ->send();
                })->hidden( fn (KotakSaran $record) => $record->status > 0 ),
            Action::make('Kembali')
                ->action(function () {
                    return redirect(url('/admin/kotak-sarans'));
                }),
        ];
    }
}
