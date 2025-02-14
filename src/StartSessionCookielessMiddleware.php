<?php

namespace iMi\LaravelTransSid;

use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class StartSessionCookielessMiddleware extends StartSessionMiddleware
{
    protected function addCookieToResponse(Response $response, Session $session)
    {
        return;
    }
}
