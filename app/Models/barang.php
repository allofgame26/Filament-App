<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barang extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['nama_barang','kode_barang','harga_barang'];

}
