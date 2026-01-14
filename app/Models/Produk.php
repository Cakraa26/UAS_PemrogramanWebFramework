<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    public $table = 'm_item';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];
}
