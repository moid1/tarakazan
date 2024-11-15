<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessOwner extends Model
{

    protected $table = "business_owners";
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class); // assuming BusinessOwner has a user_id column
    }

    public function package()
{
    return $this->belongsTo(Package::class, 'package');
}

}
