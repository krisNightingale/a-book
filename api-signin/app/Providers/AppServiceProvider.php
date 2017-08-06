<?php

namespace App\Providers;

use App\Services\MailService;
use Illuminate\Support\ServiceProvider;
use Memcached;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MailService', function (){
            return new MailService();
        });

        $this->app->singleton('memcached', function (){
            $cache = new Memcached();
            $cache->addServers(config('cache.memcached.servers'));
            return $cache;
        });
    }
}
