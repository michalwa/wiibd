<?php

namespace Routing;

use Http\Request;
use Http\Response;
use Routing\Route\Route;
use Routing\Route\PatternRoute;

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
     * Shorthand for adding a new `GET` `PatternRoute` with the specified pattern
     * 
     * @param string $pattern The route pattern
     * @param callable $callback The route handler callback
     */
    public function get(string $pattern, callable $callback): Route {
        $route = PatternRoute::new('GET', $pattern, $callback);
        $this->add($route);
        return $route;
    }

    /**
     * Finds and return a registered route with the given name
     * 
     * @param string $name The name to search for
     */
    public function getRoute(string $name): ?Route {
        foreach($this->routes as $route) {
            if($route->getName() === $name) {
                return $route;
            }
        }
        return null;
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
     * @param Request $request The request to handle.
     */
    public function handle(Request $request): ?Response {
        foreach($this->routes as $route) {
            if(($response = $route->tryHandle($request)) !== null) {
                return $response;
            }
        }

        if($this->fallback !== null) {
            return call_user_func($this->fallback, $request);
        }

        return null;
    }

}
