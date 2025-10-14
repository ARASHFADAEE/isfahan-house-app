<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'flexible_desk_capacity',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function desks(): HasMany
    {
        return $this->hasMany(Desk::class);
    }

    public function lockers(): HasMany
    {
        return $this->hasMany(Locker::class);
    }

    public function privateRooms(): HasMany
    {
        return $this->hasMany(PrivateRoom::class);
    }

    public function meetingRooms(): HasMany
    {
        return $this->hasMany(MeetingRoom::class);
    }

    public function flexibleDeskReservations(): HasMany
    {
        return $this->hasMany(FlexibleDeskReservation::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}