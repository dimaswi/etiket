<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiketUnitResource\Pages;
use App\Filament\Resources\TiketUnitResource\RelationManagers;
use App\Models\Permintaan;
use App\Models\SelesaiPermintaan;
use App\Models\TiketPermintaan;
use App\Models\TiketUnit;
use App\Models\TolakPermintaan;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
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

class TiketUnitResource extends Resource
{
    protected static ?string $model = TiketPermintaan::class;

    protected static ?string $navigationLabel = 'Tiket Unit';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 0)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 0)->count() > 0 ? 'danger' : 'primary';
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
            ->columns([
                TextColumn::make('permintaan.subjek')->searchable()->sortable(),
                TextColumn::make('masukan')->searchable()->sortable(),
                TextColumn::make('pemberi_permintaan.name')->searchable()->sortable(),
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
                    ->action(function (TiketPermintaan $record) {
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
                    ->visible(function (TiketPermintaan $record) {
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
                    ->action(function (array $data, TiketPermintaan $record) {
                        try {
                            $record->update(['status' => 99]);

                            TolakPermintaan::create([
                                'permintaan_id' => $record->permintaan_id,
                                'tiket_unit_id' => $record->id,
                                'worker' => $record->worker,
                                'alasan' => $data['alasan']
                            ]);

                            Permintaan::where('id', $record->permintaan_id)->update([
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
                    ->visible(function (TiketPermintaan $record) {
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
                            ->directory('selesai-permintaan')
                            ->image()
                    ])
                    ->action(function (array $data, TiketPermintaan $record) {
                        try {
                            $record->update([
                                'status' => 100,
                                'jam_selesai' => Carbon::now('Asia/Jakarta')
                            ]);

                            Permintaan::where('id', $record->permintaan_id)->update([
                                'status' => 100
                            ]);

                            SelesaiPermintaan::create([
                                'permintaan_id' => $record->permintaan_id,
                                'tiket_unit_id' => $record->id,
                                'worker' => $record->worker,
                                'pesan' => $data['pesan'],
                                'lampiran' => $data['lampiran'],
                            ]);

                            Notification::make()
                                ->title('Berhasil diselesaikan!')
                                ->success()
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Gagal diupdate!')
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(function (TiketPermintaan $record) {
                        return $record->status == 1;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListTiketUnits::route('/'),
            'create' => Pages\CreateTiketUnit::route('/create'),
            'view' => Pages\ViewTiketUnit::route('/{record}'),
            'edit' => Pages\EditTiketUnit::route('/{record}/edit'),
        ];
    }
}
