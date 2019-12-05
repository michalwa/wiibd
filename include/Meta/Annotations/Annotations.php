<?php

namespace Meta\Annotations;

use \Reflector;

/**
 * An annotation is an expression included in a doc-comment beginning with an `@`
 * followed by the annotation class name, then parameters in parentheses, e.g.
 * `@SomeAnnotation('foo', "bar", 123, -.42, false)`
 */
class Annotations {
    
    /**
     * Finds and parses all annotations present in the given doc string.
     * 
     * @param Reflector $item The annotated item
     * @param string $doc The doc-comment attached to the item
     * @param array $aliases An associative array of class name aliases that will be
     *  used to resolve the name of the annotation class
     */
    public static function parseAll(Reflector $item, string $doc, $aliases = []) {
        $beginRegex = '/@(([a-zA-Z_-][a-zA-Z0-9_-]+)(\\\([a-zA-Z_-][a-zA-Z0-9_-]+))*)\(/';
        $valueRegex = '/((-?(\d*\.)?\d+)|(\'(.*?)\')|("(.*?)")|true|false|null)\s*(,\s*)?/';

        $types = [];
        $all = [];

        // Find all opening expressions: "@Annotation("
        if(preg_match_all($beginRegex, $doc, $matches, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER)) {
            foreach($matches as $match) {
                $params = [];
                $className = $aliases[$match[1][0]] ?? $match[1][0];
                $offset = $match[0][1] + strlen($match[0][0]);

                $lineOffset = -substr_count(substr($doc, $match[0][1]), "\n") - 1;

                $end = false;
                while(!$end) {
                    if(!preg_match($valueRegex, $doc, $v, 0, $offset)) break;

                    if(isset($v[4]) && $v[4] !== '' || isset($v[6]) && $v[6] !== '') {  // string
                        $params[] = $v[5];
                    } else if(isset($v[2]) && $v[2] !== '' && (!isset($v[3]) || $v[3] === '')) {  // int
                        $params[] = (int)$v[2];
                    } else if(isset($v[2]) && $v[2] !== '' && isset($v[3]) && $v[3] !== '') {  // float
                        $params[] = (float)$v[2];
                    } else if($v[1] === 'true') {  // true
                        $params[] = true;
                    } else if($v[1] === 'false') {  // false
                        $params[] = false;
                    } else if($v[1] === 'null') {  // null
                        $params[] = null;
                    } else {
                        throw new AnnotationException("Invalid argument: ".$v[1], $item, $lineOffset);
                    }

                    $offset += strlen($v[0]);
                    if(substr($doc, $offset, 1) === ')') $end = true;
                }
                if(substr($doc, $offset, 1) !== ')') {
                    throw new AnnotationException("Unexpected character", $item, $lineOffset);
                }

                $class = new \ReflectionClass($className);
                if(!$class->isSubclassOf('Meta\Annotations\Annotation')) {
                    throw new AnnotationException("Type ".$className." is not a subclass of Meta\Annotations\Annotation", $item, $lineOffset);
                }

                if(!$className::allowMultiple() && in_array($className, $types)) {
                    throw new AnnotationException('Annotation @'.$className.' can only be used once on a single item.', $item, $lineOffset);
                }
                $types[] = $className;

                $all[] = new $className($item, $lineOffset, $params);
            }
        }

        return $all;
    }

}
