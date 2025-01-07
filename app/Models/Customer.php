<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $guarded = [];

    protected $fillable = ['nama_customer','kode_customer','alamat_customer','telefon_customer'];

    public function faktur(){
        return $this->hasMany(faktur::class,'id_customer','id');
    }
}
