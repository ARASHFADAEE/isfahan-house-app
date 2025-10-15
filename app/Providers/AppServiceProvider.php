<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use App\Modules\Sms\Services\SmsManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the SMS manager as a singleton and provide a container alias.
        $this->app->singleton(SmsManager::class, function ($app) {
            return new SmsManager($app);
        });
        $this->app->alias(SmsManager::class, 'sms.manager');

        // Register a global class alias for convenient static access: Sms::...
        AliasLoader::getInstance()->alias('Sms', \App\Modules\Sms\Facades\Sms::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
