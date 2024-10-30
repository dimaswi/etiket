<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TiketResource\Pages;
use App\Filament\Resources\TiketResource\RelationManagers;
use App\Models\Tiket;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
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
                        '2' => 'heroicon-o-check-circle',
                        '99' => 'heroicon-o-minus-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'warning',
                        '2' => 'success',
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
                        $record->update(['status' => 1]);
                    })
                    ->visible(function (Tiket $record) {
                        return $record->status === 0;
                    }),
                Action::make('tolak')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Textarea::make('tolak')
                            ->label('Alasan')
                            ->required(),
                    ])
                    ->action(function (Tiket $record) {
                        $record->update(['status' => 99]);
                    })
                    ->visible(function (Tiket $record) {
                        return $record->status === 0;
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
            'index' => Pages\ListTikets::route('/'),
            'create' => Pages\CreateTiket::route('/create'),
            'view' => Pages\ViewTiket::route('/{record}'),
            'edit' => Pages\EditTiket::route('/{record}/edit'),
        ];
    }
}
