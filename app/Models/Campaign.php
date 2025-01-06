<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];

    public function coupons(){
        return $this->hasMany(Coupon::class)->with('RedeemCode');
    }
    public function sms(){
        return $this->hasMany(SMSQuota::class);
    }
}
