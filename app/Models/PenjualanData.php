<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanData extends Model
{
    public $table = 't_jual_dt';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];
    public $timestamps = false;

    public function items()
    {
        return $this->belongsTo(Produk::class, 'itemfk', 'pk');
    }
}
