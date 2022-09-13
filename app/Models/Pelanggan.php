<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';
    public $timestamps = false;
    // protected $primaryKey = 'id_pelanggan';
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'id_pelanggan',
        'nama',
        'domisili',
        'jenis_kelamin',
    ];

    public static function boot()
    {
        parent::boot();

        // insert id pelanggan after creating new record in the controller
        static::created(function ($model) {
            $model->id_pelanggan .= 'PELANGGAN_' . $model->id;
            $model->save();
        });
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
