<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $table = 'm_supplier';
    protected $primaryKey = 'pk';
    protected $guarded = ['pk'];
    public $timestamps = false;
}
