<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KotakSaranResource\Pages;
use App\Filament\Resources\KotakSaranResource\RelationManagers;
use App\Models\KotakSaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KotakSaranResource extends Resource
{
    protected static ?string $model = KotakSaran::class;

    protected static ?string $navigationLabel = 'Kotak Saran';

    protected static ?string $navigationGroup = 'Main';

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

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
            ->recordUrl(
                fn(KotakSaran $record): string => Pages\ViewKotakSaran::getUrl([$record->id]),
            )
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pesan')
                    ->limit(25),
                IconColumn::make('status')
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
                    })
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ])
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
                    TextEntry::make('nama'),
                    TextEntry::make('created_at')->badge()->date(),
                    IconEntry::make('status')
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
                    TextEntry::make('nomor'),
                    TextEntry::make('email'),
                    TextEntry::make('pesan')->columnSpanFull(),
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
            'index' => Pages\ListKotakSarans::route('/'),
            'create' => Pages\CreateKotakSaran::route('/create'),
            'view' => Pages\ViewKotakSaran::route('/{record}'),
            'edit' => Pages\EditKotakSaran::route('/{record}/edit'),
        ];
    }
}
