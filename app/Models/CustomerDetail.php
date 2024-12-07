<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDetail extends Model
{
    protected $guarded = [];

    // Define the relationship with the BusinessOwner model
    public function businessOwner()
    {
        return $this->belongsTo(BusinessOwner::class);
    }

    public function redeemCoupon(){
        return $this->hasMany(RedeemCode::class, 'customer_details_id');
    }
}
