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
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewKotakSaran extends ViewRecord
{
    protected static string $resource = KotakSaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
            Action::make('lampiran')
                ->label('Lihat Lampiran')
                ->icon('heroicon-o-folder-arrow-down')
                ->color('success')
                ->url(fn (KotakSaran $record): string => url('storage'.$record->lampiran))
                ->openUrlInNewTab(),
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
                        ->warning()
                        ->body($record->pesan)
                        ->sendToDatabase($worker)
                        ->broadcast($worker);
                })->hidden(fn(KotakSaran $record) => $record->status > 0),
            Action::make('Kembali')
                ->action(function () {
                    return redirect(url('/admin/kotak-sarans'));
                }),
        ];
    }
}
