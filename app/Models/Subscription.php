<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_type_id',
        'branch_id',
        'start_datetime',
        'end_datetime',
        'status',
        'total_price',
        'discount_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionType(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(SubscriptionAddon::class);
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
}