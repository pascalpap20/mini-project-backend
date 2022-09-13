<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';
    const CREATED_AT = 'tgl';
    const UPDATED_AT = null;

    // public $timestamps = true;

    protected $fillable = [
        'id_nota',
        'tgl',
        'kode_pelanggan',
        'subtotal',
    ];

    protected $casts = [
        'tgl' => 'datetime:d/m/Y', // Change format
    ];

    public static function boot()
    {
        parent::boot();

        // insert id pelanggan after creating new record in the controller
        static::created(function ($model) {
            $model->id_nota .= 'NOTA_' . $model->id;
            $model->save();

        });
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function item_penjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'nota', 'id_nota');
    }

}
