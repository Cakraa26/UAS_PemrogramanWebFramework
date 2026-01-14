<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianData extends Model
{
    public $table = 't_beli_dt';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'itemfk', 'pk');
    }
}
