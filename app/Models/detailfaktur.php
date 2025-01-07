<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailfaktur extends Model
{
    use HasFactory;

    protected $table = 'detailfakturs';

    protected $guarded = [];

    protected $fillable = [
        'barang_id',
        'faktur_id',
        'diskon',
        'harga',
        'subtotal',
        'qty',
        'hasil_qty'
    ];

    public function barang(){
        return $this->belongsTo(barang::class, 'barang_id', 'id');
    }

    public function faktur(){
        return $this->belongsTo(faktur::class, 'faktur_id', 'id');
    }
}
