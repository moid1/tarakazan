<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'customers', 'price', 'quantity']; // Allow mass assignment for these fields
    

}
