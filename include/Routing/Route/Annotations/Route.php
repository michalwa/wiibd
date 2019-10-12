<?php

namespace Routing\Route\Annotations;

use \Reflector;
use \ReflectionFunction;
use \ReflectionMethod;
use \App;
use Meta\Annotations\Annotation;
use Meta\Annotations\AnnotationException;
use Routing\Route\PatternRoute;

/**
 * Route annotation used on controller methods.
 * Usage: `@Route(<method>, <pattern>)`, e.g. `@Route('GET', '/')`
 */
class Route extends Annotation {

    /**
     * The route method
     * @var string
     */
    private $method;

    /**
     * The route URL path pattern
     * @var string
     */
    private $pattern;

    /**
     * {@inheritDoc}
     */
    public function __construct(Reflector $item, $params) {
        parent::__construct($item);

        if(count($params) != 2) {
            throw new AnnotationException("Invalid number of parameters.");
        }
        if( !($item instanceof ReflectionFunction) && !($item instanceof ReflectionMethod) ) {
            throw new AnnotationException('The @Route annotation can only be used on functions or methods');
        }
        
        $this->method  = $params[0];
        $this->pattern = $params[1];
    }

    /**
     * Creates the described route
     * 
     * @param object|null $object The object to create the route for
     *  or `null` if the annotated item was a function
     */
    public function create(?object $object = null): \Routing\Route\Route {
        $item = $this->getItem();
        if($item instanceof ReflectionFunction) {
            $closure = $item->getClosure();
            $name = $item->name;
        } else if($item instanceof ReflectionMethod) {
            $closure = $item->getClosure($object);
            $name = $item->class.'::'.$item->name;
        }
        
        $route = PatternRoute::new($this->method, $this->pattern, $closure);
        $route->setName($name);
        App::get()->getRouter()->add($route);

        return $route;
    }

}
