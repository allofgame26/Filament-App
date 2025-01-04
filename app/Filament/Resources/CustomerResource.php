<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user'; // icon dari navigasi Customer di sidebar

    // Jika ingin mencari Icon, cari di google dengan menggunakan keyword heroicon, dan untuk format penulisan diawal dengan "heroicon-o-"

    protected static ?string $navigationLabel = 'Kelola Customer'; // label dari navigasi Customer di sidebar

    protected static ?string $navigationGroup = 'Kelola'; // melakukan Grouping didalam navigasi bar

    protected static ?string $slug = 'kelola-customer'; // membuat custom untuk address url

    protected static ?string $label = 'Kelola Customer'; // membuat custom untuk address url

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_customer')
                    ->label('Nama') //  merubah judul text input
                    ->placeholder('Masukkan Nama Anda') // memberikan tulisan teks didalam form
                    ->required(),  // harus diisi
                TextInput::make('kode_customer')
                    ->label('Kode')
                    ->placeholder('Masukkan Kode Customer')
                    ->numeric()
                    ->required(),
                TextInput::make('alamat_customer')
                    ->label('Alamat')
                    ->placeholder('Masukkan Alamat Anda')
                    ->required(),
                TextInput::make('telefon_customer')
                    ->label('Nomor Telefon')
                    ->placeholder('Masukkan Nomor Telefon Anda')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_customer')
                ->searchable() // Untuk mencari sebuah data didalam tabel nama_customer
                ->sortable() // menyotir dari ASC maupun DESC
                ->label('Nama'),
                TextColumn::make('kode_customer')
                ->copyable() // memudahkan untuk me-copy sebuah value data tersebut
                ->copyMessage('Berhasil Copy')
                ->label('Kode'),
                TextColumn::make('alamat_customer')
                ->label('Alamat'),
                TextColumn::make('telefon_customer')
                ->label('Nomor Telefon'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
