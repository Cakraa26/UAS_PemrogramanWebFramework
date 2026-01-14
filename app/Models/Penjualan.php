<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    public $table = 't_jual_hd';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'konsumenfk', 'pk');
    }
}
