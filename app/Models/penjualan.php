<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penjualan extends Model
{
    use HasFactory;

     protected $fillable = [
        'kode',
        'tanggal',
        'jumlah',
        'customer_id',
        'faktur_id',
        'status',
        'keterangan',
     ];

     protected $table = "penjualan";

     protected $guarded = [];

     public function customer (){
        return $this->belongsTo(customer::class, 'customer_id', 'id');
     }

     public function faktur(){
        return $this->belongsTo(faktur::class, 'faktur_id', 'id');
     }
}
