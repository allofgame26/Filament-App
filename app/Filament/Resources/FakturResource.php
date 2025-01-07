<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakturResource\Pages;
use App\Filament\Resources\FakturResource\RelationManagers;
use App\Models\barang;
use App\Models\Customer;
use App\Models\Faktur;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use League\CommonMark\Delimiter\Bracket;

class FakturResource extends Resource
{
    protected static ?string $model = faktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';

    protected static ?string $navigationLabel = 'Kelola Faktur'; // label dari navigasi Customer di sidebar

    protected static ?string $label = 'Kelola Faktur';

    protected static ?string $slug = 'kelola-faktur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make("kode_faktur")
                    ->required()
                    ->live()
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                        'md' => 1,
                        'xl' => 1,
                    ]),
                DatePicker::make("tanggal_faktur")
                    ->required()
                    ->live()
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                        'md' => 1,
                        'xl' => 1,
                    ]),
                TextInput::make("kode_customer")
                    ->readOnly()
                    ->live()
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                        'md' => 1,
                        'xl' => 1,
                    ]),
                Select::make("customer_id")
                    ->reactive()
                    ->label("Nama Customer")
                    ->options(Customer::all()->pluck('nama_customer', 'id'))
                    ->required()
                    ->live()
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                        'md' => 1,
                        'xl' => 1,
                    ])
                    ->afterStateUpdated(function ($state, callable $set) { // afterstateupdated digunakan untuk merubah secara langsung atau meng-update secara langsung jika ada perubahan di dalam Form Kolom select nama customer. jadi terjadi perubahan secara langsung di dalam kode Customer 
                        $customer = Customer::find($state); // memanggil model yang akan diambil datanya, dan $state adalah data yang telah diambil dari form select nama customer

                        if ($customer) { // terjadi perubahan jika customer tidak kosong
                            $set('kode_customer', $customer->kode_customer); // mengambil kode customer dari model customer dan mengubah secara langsung di dalam form kode customer
                        }
                    }),
                Repeater::make('detail_faktur') // untuk parameter nya dimasukkan relasi didalam model faktur tersebut.
                    ->relationship()
                    ->schema([ // Schema tersebut untuk membuat form baru yang berfungsi untuk menambahkan detail faktur 
                        Select::make("barang_id")
                            ->reactive()
                            ->label("Nama Barang")
                            ->relationship('barang', 'nama_barang')
                            ->required()
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                                'md' => 1,
                                'xl' => 1,
                            ])
                            ->afterStateUpdated(function ($state, callable $set) {
                                $barang = barang::find($state);

                                if ($barang) {
                                    $set('harga', $barang->harga_barang);
                                }
                            }), // select ini bisa ddibuat juga untuk mengambil id nya juga, tetapi untuk di Select ini, langsung mengambil relasi yang dibutuhkan dari model tertentu, paramaeter didalam fungsi tersebut adalah nama relasi, dan nama attribut / field
                        TextInput::make("harga")
                            ->label("Harga")
                            ->numeric()
                            ->prefix("Rp")
                            ->readOnly()
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                                'md' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make("qty")
                            ->label("Quantity")
                            ->numeric()
                            ->required()
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                                'md' => 1,
                                'xl' => 1,
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $ambildataharga = $get('harga');
                                $set('hasil_qty', intval($ambildataharga * $state)); // inval difungsikan untuk mengkonversi menjadi bilangan bulat
                            }),
                        TextInput::make("hasil_qty")
                            ->label("Total Quantity")
                            ->prefix("Rp")
                            ->numeric()
                            ->readOnly()
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                                'md' => 1,
                                'xl' => 1,
                            ]),
                        TextInput::make("diskon")
                            ->label("Diskon")
                            ->numeric()
                            ->suffix("%")
                            ->required()
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                                'md' => 1,
                                'xl' => 1,
                            ])
                            ->reactive()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                $ambildatatotalharga = $get('hasil_qty');
                                $hasildiskon =  $ambildatatotalharga * ($state / 100);
                                $set('subtotal', intval($ambildatatotalharga - $hasildiskon));
                            }),
                        TextInput::make("subtotal")
                            ->label("Sub Total")
                            ->prefix("Rp")
                            ->numeric()
                            ->readOnly()
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                                'md' => 1,
                                'xl' => 1,
                            ]),
                    ])
                    ->required()
                    ->live()
                    ->columnSpan(2),
                Textarea::make("ket_faktur")
                    ->live()
                    ->label("Keterangan Faktur")
                    ->autosize()
                    ->columnSpan(2),
                TextInput::make("total")
                    ->numeric()
                    ->readOnly()
                    ->live()
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                        'md' => 1,
                        'xl' => 1,
                    ])
                    ->placeholder(function (Set $set, Get $get) {
                        $detailtotal = collect($get('detail_faktur')) // didalam collect ada data untuk mengambil dari detail faktur
                            ->pluck('subtotal') // membuat subtotal dari beberapa detail faktur menjadi array
                            ->sum(); // menjumlahkan subtotal yang sudah diarray

                        if ($detailtotal == null) {
                            $set('total', 0);
                        } else {
                            $set('total', $detailtotal);
                        }
                    }),
                TextInput::make("nominal_charge")
                    ->numeric()
                    ->required()
                    ->live()
                    ->suffix("%")
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                        'md' => 1,
                        'xl' => 1,
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, $state, Get $get){
                        $total = $get('total');
                        $charge = $total * ($state/100);
                        $totalfinal = $total + $charge;

                        $set('charge', $charge); // menampilkan berapa charge nya
                        $set('total_final', $totalfinal); // menampilkan dari rumus total + charge
                    }),
                TextInput::make("charge")
                    ->numeric()
                    ->readOnly()
                    ->live()
                    ->columnSpan(2),
                TextInput::make("total_final")
                    ->numeric()
                    ->readOnly()
                    ->live()
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("kode_faktur"),
                TextColumn::make("tanggal_faktur"),
                TextColumn::make("kode_customer"),
                TextColumn::make("customer.nama_customer"), // memanggil nama relation didalam modelnya, dan panggil field yang diinginkan
                TextColumn::make("ket_faktur"),
                TextColumn::make("total"),
                TextColumn::make("nominal_charge"),
                TextColumn::make("charge"),
                TextColumn::make("total_final"),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(), // mengecek data yang telah dihapus
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(), //menghapus data tanpa menghapus data asli, atau masih ada history
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(), // me-restore data yang pernah dihapus
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
            'index' => Pages\ListFakturs::route('/'),
            'create' => Pages\CreateFaktur::route('/create'),
            'edit' => Pages\EditFaktur::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
