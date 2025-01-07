<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
 
class BarangResource extends Resource
{
    protected static ?string $model = Barang::class; // mengikuti ke dalam model nya, jadi setting terlebih dahulu untuk pembuatan model nya

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $navigationLabel = 'Kelola Barang'; // label dari navigasi Customer di sidebar

    protected static ?string $navigationGroup = 'Kelola Gudang'; // melakukan Grouping didalam navigasi bar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_barang'),
                TextInput::make('harga_barang'),
                TextInput::make('kode_barang'),
            ]); // membuat Form didalam create, update dan edit
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang'),
                TextColumn::make('harga_barang'),
                TextColumn::make('kode_barang'),
            ]) // membuat kolom dan menampilkan data yang ada di database
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]) // membuat tombol aksi disetiap data
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
