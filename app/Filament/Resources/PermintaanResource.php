<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermintaanResource\Pages;
use App\Filament\Resources\PermintaanResource\RelationManagers;
use App\Models\Permintaan;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Card as ComponentsCard;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
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

class PermintaanResource extends Resource
{
    protected static ?string $model = Permintaan::class;

    protected static ?string $navigationLabel = 'Permintaan Unit';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

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
                Card::make()->schema([
                    TextInput::make('subjek')
                        ->required()
                        ->placeholder('Masukan Subjek'),
                    RichEditor::make('pesan')
                        ->required()
                        ->placeholder('Masukan Pesan'),
                    FileUpload::make('lampiran')
                        ->image()
                        ->directory('lampiran-permintaan')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('peminta.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subjek')
                    ->searchable(),
                IconColumn::make('status')
                    ->icon(fn(string $state): string => match ($state) {
                        '0' => 'heroicon-o-x-circle',
                        '1' => 'heroicon-o-clock',
                        '100' => 'heroicon-o-check-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'warning',
                        '100' => 'success',
                        default => 'gray',
                    })
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->sortable()
                    ->since()
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
                ComponentsCard::make()->schema([
                    TextEntry::make('peminta.name'),
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
                    TextEntry::make('subjek'),
                    TextEntry::make('pesan')->columnSpanFull()->html(),
                    ImageEntry::make('lampiran')->columnSpanFull()
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
            'index' => Pages\ListPermintaans::route('/'),
            'create' => Pages\CreatePermintaan::route('/create'),
            'view' => Pages\ViewPermintaan::route('/{record}'),
            'edit' => Pages\EditPermintaan::route('/{record}/edit'),
        ];
    }
}
