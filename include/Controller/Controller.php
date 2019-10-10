<?php

namespace Controller;

use \ReflectionClass;
use \App;
use Http\Response;
use Http\Methods;
use Routing\Routes\PatternRoute;
use Meta\Annotations;

/**
 * Base class for controllers
 */
abstract class Controller {

    /**
     * Constructs a controller
     */
    public function __construct() {
        $class = new ReflectionClass($this);
        foreach($class->getMethods() as $method) {
            Annotations::parseAll($method, $this, $method->getDocComment(), [
                'Route' => 'Routing\Annotations\Route'
            ]);
        }
    }

    /**
     * Returns a redirect response to the rendered URL of the specified `PatternRoute`
     * 
     * @param string $name The name of the route to redirect to
     * @param array $params Parameter values for the path pattern
     */
    protected function redirect(string $name, array $params = []): Response {
        $route = App::get()->getRouter()->getRoute($name);
        if( !($route instanceof PatternRoute) ) {
            throw new ControllerException('Route "'.$route.'" is not an instance of PatternRoute');
        }
        $url = $route->getPattern()->render($params);
        return Response::redirect($url);
    }

}
