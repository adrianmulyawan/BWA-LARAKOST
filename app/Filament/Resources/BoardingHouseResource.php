<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoardingHouseResource\Pages;
use App\Filament\Resources\BoardingHouseResource\RelationManagers;
use App\Models\BoardingHouse;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class BoardingHouseResource extends Resource
{
    protected static ?string $model = BoardingHouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Informasi Umum')
                            ->schema([
                                FileUpload::make('thumbnail')
                                    ->required()
                                    ->label('Thumbnail')
                                    ->image()
                                    ->directory('thumbnails'),
                                TextInput::make('name')
                                    ->label('Nama Kost')
                                    ->required()
                                    ->placeholder('Nama Kost')
                                    ->debounce(500)
                                    ->lazy()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $slugName = Str::slug($state);
                                        $set('slug', $slugName);
                                    }),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->placeholder('Slug Name (Auto Generate)')
                                    ->required(),
                                Select::make('city_id')
                                    ->label('Kota')
                                    ->required()
                                    ->relationship('city', 'name'),
                                Select::make('category_id')
                                    ->label('Kategori')
                                    ->required()
                                    ->relationship('category', 'name'),
                                RichEditor::make('description')
                                    ->label('Deskripsi Hunian')
                                    ->required(),
                                TextInput::make('price')
                                    ->label('Harga Sewa')
                                    ->numeric()
                                    ->required()
                                    ->prefix('IDR')
                                    ->placeholder('Harga Sewa'),
                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->required()
                                    ->placeholder('Alamat Hunian'),
                            ]),
                        Tabs\Tab::make('Bonus')
                            ->schema([
                                Repeater::make('bonuses')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->required()
                                            ->image()
                                            ->directory('bonuses')
                                            ->label('Gambar'),
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Nama'),
                                        Textarea::make('description')
                                            ->required()
                                            ->label('Deskripsi')
                                    ])
                                    ->columns(2)
                            ]),
                        Tabs\Tab::make('Tab 3')
                            ->schema([
                                // ...
                            ]),
                    ])
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBoardingHouses::route('/'),
            'create' => Pages\CreateBoardingHouse::route('/create'),
            'edit' => Pages\EditBoardingHouse::route('/{record}/edit'),
        ];
    }
}
