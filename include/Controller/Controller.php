<?php

namespace Controller;

use \App;
use Http\Response;
use Meta\Annotations\ReflectionClassAnnotated;
use Routing\Route\PatternRoute;

/**
 * Base class for controllers
 */
abstract class Controller {

    /**
     * Annotation class aliases for annotation parsing
     */
    private const ANNOTATION_ALIASES = [
        'Route' => 'Routing\Route\Annotations\Route'
    ];

    /**
     * Constructs a controller
     */
    public function __construct() {
        $class = new ReflectionClassAnnotated($this);
        foreach($class->getMethodsAnnotated(null, self::ANNOTATION_ALIASES) as $method) {
            /** @var \Routing\Route\Annotations\Route $annotation */
            foreach($method->getAnnotations('Routing\Route\Annotations\Route') as $annotation) {
                $annotation->create($this);
            }
        }
    }

    /**
     * Returns a redirect response to the rendered URL of the specified `PatternRoute`
     * defined in this controller class.
     *
     * @param string $name The name of the route to redirect to
     * @param array $params Parameter values for the path pattern
     *
     * @throws ControllerException If the route is not found or is invalid
     */
    protected function redirectToSelf(
        string $name,
        array $params = [],
        array $query = [],
        ?string $fragment = null
    ): Response {
        return Response::redirect($this->routeUrl($name, $params, $query, $fragment));
    }

    /**
     * Returns a URL to the specified route defined in the controller class.
     *
     * @param array $params The parameters to plug into the path pattern
     * @param array $query The query parameters to append to the URL
     * @param null|string $fragment The fragment specified to append to the URL
     *
     * @return string The resulting URL
     */
    public static function routeUrl(
        string $name,
        array $params = [],
        array $query = [],
        ?string $fragment = null
    ): string {
        return App::routeUrl(get_called_class().'::'.$name, $params, $query, $fragment);
    }

}
