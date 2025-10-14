<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'type',
        'event_type',
        'status',
        'external_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}