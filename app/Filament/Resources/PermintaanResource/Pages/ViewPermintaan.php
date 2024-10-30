<?php

namespace App\Filament\Resources\PermintaanResource\Pages;

use App\Filament\Resources\PermintaanResource;
use App\Models\Permintaan;
use App\Models\TiketPermintaan;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPermintaan extends ViewRecord
{
    protected static string $resource = PermintaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            ->action(function (array $data, Permintaan $record) {
                $tiket = TiketPermintaan::create([
                    'permintaan_id' => $record->id,
                    'worker' => $data['worker'],
                    'masukan' => $data['masukan'],
                    'pemberi' => auth()->user()->id,
                ]);

                $record->update(['status' => 1]);

                $worker = User::where('id', $data['worker'])->first();

                Notification::make()
                    ->title('Permintaan berhasil dikirim!')
                    ->success()
                    ->send();

                Notification::make()
                    ->title('Permintaan pekerjaan baru masuk!')
                    ->body($record->subjek)
                    ->warning()
                    ->sendToDatabase($worker)
                    ->broadcast($worker);

            })->hidden( fn (Permintaan $record) => $record->status > 0 ),
        ];
    }
}
