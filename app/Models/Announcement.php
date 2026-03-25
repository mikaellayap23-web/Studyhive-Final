<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method mixed user()
 */
class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }
}
