<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    protected $fillable = ['name', 'phone', 'business_owner_id'];

    // Define the relationship with the BusinessOwner model
    public function businessOwner()
    {
        return $this->belongsTo(BusinessOwner::class);
    }
}
