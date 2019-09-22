<?php

namespace Routing;

use \App;
use Http\Request;
use Http\Response;

/**
 * Handles routing
 */
class Router {

    /**
     * Registered routes
     * @var Route[]
     */
    private $routes = [];

    /**
     * Fallback callback
     * @var callable
     */
    private $fallback = null;

    /**
     * Registers the given route
     * 
     * @param Route $route The route to register
     */
    public function add(Route $route): void {
        $this->routes[] = $route;
    }

    /**
     * Registers the given route as the fallback route. This will be called
     * when no route matches a request with the app and request as arguments.
     * This overwrites the previous fallback route.
     * 
     * @param callable $route The route to set as the fallback route
     */
    public function setFallback(callable $fallback): void {
        $this->fallback = $fallback;
    }

    /**
     * Handles the given HTTP request and returns a response.
     * 
     * @param App $app The app
     * @param Request $request The request to handle.
     */
    public function handle(App $app, Request $request): ?Response {
        foreach($this->routes as $route) {
            if(($response = $route->tryHandle($app, $request)) !== null) {
                return $response;
            }
        }

        if($this->fallback !== null) {
            return call_user_func($this->fallback, $app, $request);
        }

        return null;
    }

}
