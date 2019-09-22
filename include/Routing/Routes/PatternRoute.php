<?php

namespace Routing\Routes;

use \App;
use Routing\Route;
use Http\Request;
use Http\Response;
use Files\PathPattern;

/**
 * A route matching a path pattern
 */
abstract class PatternRoute extends Route {

    /**
     * The pattern the path must match for this route to handle a request
     * @var PathPattern
     */
    private $pattern;

    /**
     * Constructs a new pattern-matching route
     * 
     * @param PathPattern $pattern The pattern the path must match
     *  for this route to handle a request
     */
    public function __construct(PathPattern $pattern) {
        $this->pattern = $pattern;
    }

    public function tryHandle(App $app, Request $request): ?Response {
        if($this->pattern->match($request->getPath(), $params)) {
            return $this->handle($app, $request, $params);
        }

        return null;
    }

    /**
     * Actually handles the request with the path matching the pattern of this route
     * 
     * @param App $app The app
     * @param Request $request The request to handle
     * @param array $params Values of the pattern parameters as an associative array
     */
    protected abstract function handle(App $app, Request $request, $params): Response;

    /**
     * Implements `PatternRoute` with the given pattern and callback
     * 
     * @param string $pattern The pattern the path must match
     *  for this route to handle a request
     * @param callable $callback A function implementing
     *  `PatternRoute::handle(App $app, Request $request, $params)`
     */
    public static function new(string $pattern, callable $callback): self {
        return new class($pattern, $callback) extends PatternRoute {
            private $cb;

            function __construct($pattern, $cb) {
                parent::__construct(new PathPattern($pattern));
                $this->cb = $cb;
            }

            protected function handle(App $app, Request $request, $params): Response {
                return call_user_func($this->cb, $app, $request, $params);
            }
        };
    }

}
