<?php

namespace iMi\LaravelTransSid;

use Illuminate\Routing\UrlGenerator;

class UrlGeneratorService extends UrlGenerator
{
    private function addSid(string $url, ?\Illuminate\Routing\Route $route = null): string
    {
        // Only apply transsid to routes/routegroups with the urlsession middleware.
        // or if no route is known (for example whne processing paths)
        if ($route !== null && (!is_array($route->getAction('middleware')) || !in_array(UrlSession::class, $route->getAction('middleware')))) {
            return $url;
        }

        // Get the current query string and parameters
        $queryString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $queryParameters);

        // Add the session to the query string if needed
        if (!isset($queryParameters['NO_ADD_SID'])) {
            $queryParameters[\Config::get('session.cookie')] = \Session::getId();
        } else {
            unset($queryParameters['NO_ADD_SID']);
        }

        // Rebuild the URL
        $url = str_replace('?' . $queryString, '', $url);
        $url .= '?' . http_build_query($queryParameters);

        return $url;
    }

    public function to($path, $extra = array(), $secure = null)
    {
        $url = parent::to($path, $extra, $secure);

        return $this->addSid($url);
    }

    public function toRoute($route, $parameters, $absolute)
    {
        $url = parent::toRoute($route, $parameters, $absolute);

        return $this->addSid($url, $route);
    }

    public function previous($fallback = false)
    {
        $url = parent::previous($fallback);

        return $this->addSid($url);
    }
}
