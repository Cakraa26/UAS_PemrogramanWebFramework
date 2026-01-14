<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    public $table = 'm_konsumen';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];
}
