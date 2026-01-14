<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    public $table = 't_beli_hd';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplierfk', 'pk');
    }
    public function details()
    {
        return $this->hasMany(PembelianData::class, 'notrs', 'notrs');
    }
}
