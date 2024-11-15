<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMediaCount extends Model
{
    use HasFactory;

    // Define the table name if it doesn't follow Laravel's naming convention
    protected $table = 'social_media_counts';

    // Define the fillable attributes
    protected $fillable = ['business_owner_id', 'platform', 'count'];

    // Disable timestamps if not needed (optional)
    public $timestamps = true;

    /**
     * Update or insert the count for a given platform and business owner.
     *
     * @param int $businessOwnerId
     * @param string $platform
     * @param int $count
     * @return void
     */
    public static function updateOrInsertCount($businessOwnerId, $platform, $count)
    {
        // Use updateOrInsert to handle both insert and update in one method
        self::updateOrInsert(
            ['business_owner_id' => $businessOwnerId, 'platform' => $platform],
            ['count' => $count]
        );
    }
}
