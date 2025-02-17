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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
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
                                    ->label('Bonus')
                                    ->relationship('bonuses')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->required()
                                            ->image()
                                            ->directory('bonuses')
                                            ->label('Gambar')
                                            ->columnSpan(2),
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Nama'),
                                        Textarea::make('description')
                                            ->required()
                                            ->label('Deskripsi')
                                    ])
                            ]),
                        Tabs\Tab::make('Kamar')
                            ->schema([
                                Repeater::make('rooms')
                                    ->label('Kamar')
                                    ->relationship('rooms')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Nama'),
                                        TextInput::make('room_type')
                                            ->required()
                                            ->label('Tipe Kamar'),
                                        TextInput::make('square_feet')
                                            ->required()
                                            ->numeric()
                                            ->label('Ukuran Kamar'),
                                        TextInput::make('capacity')
                                            ->required()
                                            ->numeric()
                                            ->label('Kapasitas Kamar'),
                                        TextInput::make('price_per_month')
                                            ->required()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->label('Harga'),
                                        Toggle::make('is_available')
                                            ->required()
                                            ->label('Tersedia')
                                            ->onIcon('heroicon-m-check')
                                            ->offIcon('heroicon-m-x-mark'),
                                        Repeater::make('roomImages')
                                            ->label('Gambar Kamar')
                                            ->relationship('roomImages')
                                            ->schema([
                                                FileUpload::make('image')
                                                    ->label('Gambar Kamar')
                                                    ->required()
                                                    ->image()
                                                    ->directory('room_images'),
                                            ]),
                                    ])
                            ]),
                    ])
                    ->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Gambar'),
                TextColumn::make('name')
                    ->label('Nama Hunian')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Type')
                    ->searchable(),
                TextColumn::make('city.name')
                    ->label('Lokasi')
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
