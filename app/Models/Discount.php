<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_type_id',
        'discount_percentage',
        'valid_until',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionType(): BelongsTo
    {
        return $this->belongsTo(SubscriptionType::class);
    }
}