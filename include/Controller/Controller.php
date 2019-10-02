<?php

namespace Controller;

use \App;
use \ReflectionClass;
use Http\Response;
use Http\Methods;
use Routing\Routes\PatternRoute;

/**
 * Base class for controllers
 */
abstract class Controller {

    /**
     * The app
     * @var App
     */
    protected $app;

    /**
     * Constructs a controller
     * 
     * @param App $app The app
     */
    public function __construct(App $app) {
        $this->app = $app;

        // Find annotated routes
        $class = new ReflectionClass($this);
        foreach($class->getMethods() as $method) {
            if($doc = $method->getDocComment()) {
                $regex = '/Route: (?<method>'.Methods::getRegex().') "(?<pattern>.*?)"/';
                if(preg_match_all($regex, $doc, $matches, PREG_SET_ORDER)) {
                    foreach($matches as $match) {
                        $closure = $method->getClosure($this);
                        $route = PatternRoute::new($match['method'], $match['pattern'], $closure);
                        $route->setName($class->getName().'::'.$method->getName());
                        $app->getRouter()->add($route);
                    }
                }
            }
        }
    }

    /**
     * Returns a redirect response to the rendered URL of the specified `PatternRoute`
     * 
     * @param string $name The name of the route to redirect to
     * @param array $params Parameter values for the path pattern
     */
    protected function redirect(string $name, array $params = []): Response {
        $route = $this->app->getRouter()->getRoute($name);
        if( !($route instanceof PatternRoute) ) {
            throw new ControllerException('Route "'.$route.'" is not an instance of PatternRoute');
        }
        $url = $route->getPattern()->render($params);
        return Response::redirect($url);
    }

}
