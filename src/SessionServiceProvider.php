<?php

namespace iMi\LaravelTransSid;

use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Session\SessionManager;

class SessionServiceProvider extends \Illuminate\Session\SessionServiceProvider
{
    public function register()
    {
        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->app->singleton(StartSession::class, function () {
            return new StartSessionMiddleware($this->app->make(SessionManager::class), function () {
                return $this->app->make(CacheFactory::class);
            });
        });
    }
}
