<?php

namespace Controller;

use \ReflectionClass;
use \App;
use Http\Response;
use Http\Methods;
use Routing\Route\PatternRoute;
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
     * @param mixed[string] $params Parameter values for the path pattern
     */
    protected function redirect(string $name, $params = []): Response {
        $route = App::get()->getRouter()->getRoute($name);
        if($route === null) {
            throw new ControllerException('Route "'.$name.'" not found.');
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
     * The given name is prepended with `self` class name.
     * 
     * @param string $name The name of the route to redirect to
     * @param mixed[string] $params Parameter values for the path pattern
     */
    protected function redirectToSelf(string $name, $params = []): Response {
        return $this->redirect(get_called_class().'::'.$name, $params);
    }

}
