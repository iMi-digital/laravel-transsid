<?php

namespace iMi\LaravelTransSid;

use Illuminate\Support\ServiceProvider;

class UrlServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerUrlGenerator();
    }

    public function registerUrlGenerator()
    {
        $this->app->bind('Illuminate\Routing\UrlGenerator', 'iMi\LaravelTransSid\UrlGeneratorService');

        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();

            // The URL generator needs the route collection that exists on the router.
            // Keep in mind this is an object, so we're passing by references here
            // and all the registered routes will be available to the generator.
            $this->app->instance('routes', $routes);

            $url = new UrlGeneratorService(
                $routes, $app->rebinding(
                'request', function ($app, $request) {
                $app['url']->setRequest($request);
            }
            )
            );

            $url->setSessionResolver(function () {
                return $this->app['session'];
            });

            // If the route collection is "rebound", for example, when the routes stay
            // cached for the application, we will need to rebind the routes on the
            // URL generator instance so it has the latest version of the routes.
            $app->rebinding('routes', function ($app, $routes) {
                $app['url']->setRoutes($routes);
            });

            return $url;
        });
    }

}
