<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
