<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // untuk melakukan soft deletes didalam filament, ini dibutuhkan untuk membuat fitur soft delete 


class faktur extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'fakturs';

    protected $guarded = [];

    protected $fillable = [
        'kode_faktur',
        'tanggal_faktur',
        'kode_customer',
        'customer_id',
        'ket_faktur',
        'total',
        'nominal_charge',
        'charge',
        'total_final'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function detail_faktur(){
        return $this->hasMany(detailfaktur::class,'faktur_id','id');

    }

    public function penjualan(){
        return $this->hasMany(penjualan::class,'faktur_id','id');
    }
}
