<?php

namespace App\Modules\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Modules\Sms\Services\SmsManager to(string|array $recipients)
 * @method static \App\Modules\Sms\Services\SmsManager via(string $provider)
 * @method static \App\Modules\Sms\Services\SmsManager message(string $text)
 * @method static \App\Modules\Sms\Services\SmsManager template(string $name)
 * @method static \App\Modules\Sms\Services\SmsManager vars(array $vars)
 * @method static \App\Modules\Sms\Services\SmsManager option(string $key, mixed $value)
 * @method static array send()
 * @method static void queue(?string $queue = null, ?int $delaySeconds = null)
 */
class Sms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'sms.manager';
    }
}