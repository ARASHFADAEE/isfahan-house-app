<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_room_id',
        'reservation_date',
        'duration_hours',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meetingRoom(): BelongsTo
    {
        return $this->belongsTo(MeetingRoom::class);
    }
}