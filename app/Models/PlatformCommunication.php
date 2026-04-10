<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'superadmin_user_id',
        'title',
        'message',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function superadmin()
    {
        return $this->belongsTo(User::class, 'superadmin_user_id');
    }
}
