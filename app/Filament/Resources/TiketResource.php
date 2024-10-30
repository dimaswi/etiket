<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiketResource\Pages;
use App\Filament\Resources\TiketResource\RelationManagers;
use App\Models\KotakSaran;
use App\Models\SelesaiTiket;
use App\Models\Tiket;
use App\Models\TolakTiket;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TiketResource extends Resource
{
    protected static ?string $model = Tiket::class;

    protected static ?string $navigationLabel = 'Tiket Umum';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 0)->where('worker', auth()->user()->id)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 0)->where('worker', auth()->user()->id)->count() > 0 ? 'danger' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {

                return $query->where('worker', auth()->user()->id);
            })
            ->recordUrl(
                fn(Tiket $record): string => Pages\ViewTiket::getUrl([$record->id]),
            )
            ->columns([
                TextColumn::make('kotakSaran.nama')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('masukan')
                    ->limit(25),
                IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        '0' => 'heroicon-o-x-circle',
                        '1' => 'heroicon-o-clock',
                        '100' => 'heroicon-o-check-circle',
                        '99' => 'heroicon-o-minus-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'warning',
                        '100' => 'success',
                        '99' => 'gray',
                    })
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->badge()
                    ->since()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('terima')
                    ->label('Terima')
                    ->color('primary')
                    ->icon('heroicon-o-check-circle')
                    ->action(function (Tiket $record) {
                        try {
                            $record->update([
                                'status' => 1,
                                'jam_mulai' => Carbon::now('Asia/Jakarta')
                            ]);

                            Notification::make()
                                ->title('Berhasil diupdate!')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Gagal diupdate!')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(function (Tiket $record) {
                        return $record->status == 0;
                    }),
                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Textarea::make('alasan')
                            ->label('Alasan')
                            ->required(),
                    ])
                    ->action(function (array $data, Tiket $record) {
                        try {
                            $record->update(['status' => 99]);

                            TolakTiket::create([
                                'kotak_saran_id' => $record->kotak_sarans_id,
                                'tiket_id' => $record->id,
                                'worker' => $record->worker,
                                'alasan' => $data['alasan']
                            ]);

                            KotakSaran::where('id', $record->kotak_sarans_id)->update([
                                'status' => 0
                            ]);

                            Notification::make()
                                ->title('Berhasil diupdate!')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Gagal diupdate!')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(function (Tiket $record) {
                        return $record->status == 0;
                    }),
                Action::make('selesai')
                    ->label('selesai')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->form([
                        Textarea::make('pesan')
                            ->label('Pesan')
                            ->required(),
                        FileUpload::make('lampiran')
                            ->label('Lampiran')
                            ->directory('selesai-kotak-saran')
                            ->image()
                    ])
                    ->action(function (array $data,Tiket $record) {
                        try {
                            $record->update([
                                'status' => 100,
                                'jam_selesai' => Carbon::now('Asia/Jakarta')
                            ]);

                            KotakSaran::where('id', $record->kotak_sarans_id)->update([
                                'status' => 100
                            ]);

                            SelesaiTiket::create([
                                'kotak_saran_id' => $record->kotak_sarans_id,
                                'tiket_id' => $record->id,
                                'worker' => $record->worker,
                                'pesan' => $data['pesan'],
                                'lampiran' => $data['lampiran'],
                            ]);

                            Notification::make()
                                ->title('Berhasil diupdate!')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title($th->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(function (Tiket $record) {
                        return $record->status == 1;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Card::make()->schema([
                    TextEntry::make('kotakSaran.nama')->label('Nama Pengunjung'),
                    TextEntry::make('created_at')->label('Permintaan dibuat')->badge()->date(),
                    IconEntry::make('status')->label('Status Permintaan')
                        ->icon(fn(string $state): string => match ($state) {
                            '0' => 'heroicon-o-x-circle',
                            '1' => 'heroicon-o-clock',
                            '2' => 'heroicon-o-check-circle',
                        })
                        ->color(fn(string $state): string => match ($state) {
                            '0' => 'danger',
                            '1' => 'warning',
                            '2' => 'success',
                            default => 'gray',
                        }),
                    TextEntry::make('peminta.name'),
                    TextEntry::make('masukan')->label('Masukan dari peminta')->columnSpanFull()->html(),
                    TextEntry::make('kotakSaran.pesan')->label('Pesan dari pengunjung')->columnSpanFull(),
                ])->columns(3)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTikets::route('/'),
            'create' => Pages\CreateTiket::route('/create'),
            'view' => Pages\ViewTiket::route('/{record}'),
            'edit' => Pages\EditTiket::route('/{record}/edit'),
        ];
    }
}
