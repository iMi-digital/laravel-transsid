<?php

namespace iMi\LaravelTransSid;

class StartSessionMiddleware extends \Illuminate\Session\Middleware\StartSession
{

    public function getSession(\Illuminate\Http\Request $request)
    {
        $session = $this->manager->driver();
        $session->setId($request->input($session->getName()));
        return $session;
    }
}