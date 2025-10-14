<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function meetingReservations(): HasMany
    {
        return $this->hasMany(MeetingReservation::class);
    }

    public function flexibleDeskReservations(): HasMany
    {
        return $this->hasMany(FlexibleDeskReservation::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
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
