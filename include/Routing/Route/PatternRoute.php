<?php

namespace Routing\Route;

use \App;
use Files\Path;
use Routing\Route\Route;
use Http\Request;
use Http\Response;
use Files\PathPattern;

/**
 * A route matching a path pattern
 */
abstract class PatternRoute extends Route {

    /**
     * The request method this route handles
     * @var string
     */
    private $method;

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
    public function __construct(string $method, PathPattern $pattern) {
        $this->method = $method;
        $this->pattern = $pattern;
    }

    public function tryHandle(Request $request): ?Response {
        if($request->getMethod() === $this->method
            && $this->pattern->match($request->getPath(), $params)
        ) {
            $request->setRouteName($this->getName());
            return $this->handle($request, $params);
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function unparseUrl(array $params = []): string {
        return '/'.(new Path(App::getRootUrl(), $this->pattern->render($params)));
    }

    /**
     * Returns the pattern
     */
    public function getPattern(): PathPattern {
        return $this->pattern;
    }

    /**
     * Actually handles the request with the path matching the pattern of this route
     *
     * @param Request $request The request to handle
     * @param array $params Values of the pattern parameters as an associative array
     */
    protected abstract function handle(Request $request, $params): Response;

    /**
     * Implements `PatternRoute` with the given pattern and callback
     *
     * @param string $method The request method the route will handle
     * @param string $pattern The pattern the path must match
     *  for this route to handle a request
     * @param callable $callback A function implementing
     *  `PatternRoute::handle(App $app, Request $request, $params)`
     */
    public static function new(string $method, string $pattern, callable $callback): self {
        return new class($method, $pattern, $callback) extends PatternRoute {
            private $cb;

            function __construct($method, $pattern, $cb) {
                parent::__construct($method, new PathPattern($pattern));
                $this->cb = $cb;
            }

            protected function handle(Request $request, $params): Response {
                return call_user_func($this->cb, $request, $params);
            }
        };
    }

}
