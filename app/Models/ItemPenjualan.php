<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPenjualan extends Model
{
    use HasFactory;
    protected $table = 'item_penjualan';
    public $timestamps = false;

    protected $fillable = [
        'nota',
        'kode_barang',
        'qty',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_nota');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'kode', 'kode_barang');
    }

    public function barang_single()
    {
        return $this->hasOne(Barang::class, 'kode', 'kode_barang');
    }
}
