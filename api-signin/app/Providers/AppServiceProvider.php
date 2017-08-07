<?php

namespace App\Providers;

use App\Models\UserSession;
use App\Services\MailService;
use Illuminate\Redis\RedisManager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
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

        $this->app->bind('UserSession', function($app){
            //A session key retrieved when authorized
            $token = request()->header('X-CSRF-TOKEN');

            if (!$token){
                return null;
            }
            return new UserSession($token);
        });

//        $this->app->singleton('redis_cache', function ($app) {
//            $config = $app->make('config')->get('database.redis');
//
//            return new RedisManager(Arr::pull($config, 'client', 'predis'), $config);
//        });
    }
}
