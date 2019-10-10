<?php

namespace Routing\Annotations;

use \Reflector;
use \ReflectionFunction;
use \ReflectionMethod;
use \App;
use Meta\Annotation;
use Meta\AnnotationException;
use Routing\Routes\PatternRoute;

/**
 * Route annotation used on controller methods.
 * Usage: `@Route(<method>, <pattern>)`, e.g. `@Route('GET', '/')`
 */
class Route extends Annotation {

    public static function instantiate(Reflector $item, ?object $object, $params): Annotation {
        if($item instanceof ReflectionFunction) {
            $closure = $item->getClosure();
            $name = $item->name;
        } else if($item instanceof ReflectionMethod) {
            $closure = $item->getClosure($object);
            $name = $item->class.'::'.$item->name;
        } else {
            throw new AnnotationException('The @Route annotation can only be used on functions or methods');
        }
        
        $route = PatternRoute::new($params[0], $params[1], $closure);
        $route->setName($name);
        App::get()->getRouter()->add($route);

        return new self();
    }

}
