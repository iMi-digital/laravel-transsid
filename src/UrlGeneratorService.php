<?php

namespace iMi\LaravelTransSid;

use App\Http\Kernel;
use Illuminate\Routing\UrlGenerator;

class UrlGeneratorService extends UrlGenerator
{
    private function addSid(string $url, ?\Illuminate\Routing\Route $route = null): string
    {
        if (!$this->hasUrlSessionMiddleware($route)) {
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

    private function hasUrlSessionMiddleware(?\Illuminate\Routing\Route $route = null): bool
    {

        // Apply transsid when the urlsession middleware is defined globally.
        /** @var Kernel $kernel */
        $kernel = app(Kernel::class);
        if($kernel->hasMiddleware(UrlSession::class)) {
            return true;
        }

        // If no route is known (for example when processing paths)
        if($route === null) {
            return true;
        }

        // Apply transsid to routes/routegroups with the urlsession middleware.
        /** @var ?array $middleware */
        $middleware = $route->getAction('middleware');
        return is_array($middleware) && in_array(UrlSession::class, $middleware);
    }

    public function to($path, $extra = [], $secure = null)
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
