<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'requires_admin_approval',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
}