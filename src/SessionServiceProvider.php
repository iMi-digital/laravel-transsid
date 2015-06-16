<?php

namespace iMi\LaravelTransSid;

class SessionServiceProvider extends \Illuminate\Session\SessionServiceProvider
{
    public function register()
    {
        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->app->singleton('Illuminate\Session\Middleware\StartSession', 'iMi\LaravelTransSid\StartSessionMiddleware');
    }
}