<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = "Laporan Penjualan";

    protected static ?string $navigationGroup = "Faktur";

    protected static ?string $label = "Laporan Penjualan";

    protected static ?string $slug = "laporan-penjualan";

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
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->sortable()
                    ->searchable()
                    ->date('l d F Y'),
                TextColumn::make('kode')
                    ->label('Kode Faktur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jumlah')
                    ->sortable()
                    ->searchable()
                    ->label('Jumlah')
                    ->numeric()
                    ->money('IDR'),
                TextColumn::make('customer.nama_customer')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Customer'),
                TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->label('Status Pembayaran')
                    ->color(fn (string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    }) // membuat Warna badge berdasarkan status
                    ->formatStateUsing(fn(Penjualan $record): string => $record->status == 1 ? 'Lunas' : 'Belum Bayar'), // mengeluarkan output yang diinginkan sesuai dengan enum yang ada di database
            ])
            ->emptyStateHeading('Tidak Ada Data') // membuat Heading Custom jika data tidak ada
            ->emptyStateDescription('Silahkan tambhakn faktur terlbih dahulu') // membuat Deskripsi Custom jika data tidak ada
            ->emptyStateIcon('heroicon-o-bolt-slash') // membuat icon custom jika data tidak ada
            ->emptyStateActions([
                Action::make('create')
                    ->label('Buat Faktur Baru')
                    ->url(route('filament.admin.resources.kelola-faktur.create')) // cek Route dengan cara "php artisan r:l
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]) // function tersebut untuk membuat tombol tambah data dan terddapat url jika ingin mengarahkan ke halaman lain
            ->filters([
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
