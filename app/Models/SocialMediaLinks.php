<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialMediaLinks extends BaseModel
{
    protected $fillable = [
        'gym_id',
        'instagram_link',
        'facebook_link',
        'twitter_link',
    ];

    /**
     * @return BelongsTo
     */
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }
}
