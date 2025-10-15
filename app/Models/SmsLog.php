<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipients',
        'message',
        'template',
        'variables',
        'provider',
        'status',
        'provider_message_id',
        'raw_response',
        'error',
        'sent_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'variables' => 'array',
        'sent_at' => 'datetime',
    ];
}