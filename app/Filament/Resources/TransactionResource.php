<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Room;
use App\Models\Transaction;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Boarding House Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->required()
                    ->label('Kode'),
                Select::make('boarding_house_id')
                    ->required()
                    ->label('Pilih Hunian')
                    ->relationship('boardingHouse', 'name')
                    ->reactive(),
                Select::make('room_id')
                    ->required()
                    ->label('Pilih Ruangan')
                    ->options(function (callable $get) {
                        $boardingHouseId = $get('boarding_house_id');
                        if (!$boardingHouseId) {
                            return [];
                        }

                        return Room::where('boarding_house_id', $boardingHouseId)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->reactive()
                    ->disabled(fn(callable $get) => empty($get('boarding_house_id'))), // disable jika hunian belom dipilih/diisi
                TextInput::make('name')
                    ->required()
                    ->label('Nama'),
                TextInput::make('emal')
                    ->required()
                    ->label('Email')
                    ->email(),
                TextInput::make('phone_number')
                    ->required()
                    ->label('Nomor Handphone'),
                Select::make('payment_method')
                    ->required()
                    ->label('Jenis Pembayaran')
                    ->options([
                        'down_payment' => 'Down Payment (DP)',
                        'full_payment' => 'Full Payment'
                    ]),
                Select::make('payment_status')
                    ->required()
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid'
                    ]),
                DatePicker::make('start_date')
                    ->required()
                    ->label('Tanggal Mulai')
                    ->timezone('Asia/Jakarta'),
                TextInput::make('duration')
                    ->required()
                    ->label('Durasi')
                    ->numeric(),
                TextInput::make('total_amout')
                    ->required()
                    ->label('Total Biaya')
                    ->numeric()
                    ->prefix('IDR'),
                DatePicker::make('transaction_date')
                    ->required()
                    ->label('Tanggal Transaksi')
                    ->timezone('Asia/Jakarta'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Transaksi'),
                TextColumn::make('boardingHouse.name')
                    ->label('Nama Hunian'),
                TextColumn::make('room.name')
                    ->label('Nama Ruangan'),
                TextColumn::make('name')
                    ->label('Nama'),
                TextColumn::make('payment_method')
                    ->label('Pembayaran'),
                TextColumn::make('payment_status')
                    ->label('Status'),
                TextColumn::make('total_amount')
                    ->label('Biaya')
                    ->money('IDR'),
                TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
