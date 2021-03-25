<?php

namespace iMi\LaravelTransSid;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;

class UrlGeneratorService extends UrlGenerator
{
    public function addSid($url, ?\Illuminate\Routing\Route $route = null)
    {
        // Only apply transsid to routes/routegroups with the urlsession middleware.
        if ($route === null || !in_array(UrlSession::class, $route->getAction('middleware'))) {
            return $url;
        }

        if (strpos($url, \Config::get('session.cookie')) !== false) {
            return $url;
        }
        
        $sep = (strpos($url, '?') !== false) ? '&' : '?';
        $url .= $sep . \Config::get('session.cookie') . '=' . \Session::getId();

        return $url;
    }

    public function to($path, $extra = array(), $secure = null)
    {
        if (isset($extra['NO_ADD_SID'])) {
            unset($extra['NO_ADD_SID']);
            return parent::to($path, $extra, $secure);
        }
        $url = parent::to($path, $extra, $secure);
        $url = $this->addSid($url);
        return $url;
    }

    public function toRoute($route, $parameters, $absolute)
    {
        if (isset($parameters['NO_ADD_SID'])) {
            unset($parameters['NO_ADD_SID']);
            return parent::toRoute($route, $parameters, $absolute);
        }

        $url = parent::toRoute($route, $parameters, $absolute);
        $url = $this->addSid($url, $route);
        return $url;
    }


    public function previous($fallback = false)
    {
        $url = parent::previous($fallback);
        $url = $this->addSid($url);
        return $url;
    }


}
