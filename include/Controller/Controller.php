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
     * 
     * @param string $name The name of the route to redirect to
     * @param array $params Parameter values for the path pattern
     * 
     * @throws ControllerException If the route is not found or is invalid
     */
    protected function redirect(string $name, $params = []): Response {
        $route = App::get()->getRouter()->getRoute($name);
        if($route === null) {
            throw new ControllerException("Route '".$name."' not found");
        }
        if( !($route instanceof PatternRoute) ) {
            throw new ControllerException($route.' is not an instance of PatternRoute');
        }
        $url = '/'.App::get()->getRootUrl()->append($route->getPattern()->render($params));
        return Response::redirect($url);
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
    protected function redirectToSelf(string $name, $params = []): Response {
        return $this->redirect(get_called_class().'::'.$name, $params);
    }

}
