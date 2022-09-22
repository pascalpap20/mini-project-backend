<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'harga',
        'warna',
    ];

    public static function boot()
    {
        parent::boot();

        // insert kode after creating new record in the controller
        static::created(function ($model) {
            $model->kode .= 'BRG_' . $model->id;
            $model->save();
        });
    }

    public function item_penjualan()
    {
        return $this->hasMany(ItemPenjualan::class);
    }
}
