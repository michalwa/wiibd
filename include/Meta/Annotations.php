<?php

namespace Meta;

/**
 * An annotation is an expression included in a doc-comment beginning with an `@`
 * followed by the annotation class name, then parameters in parentheses, e.g.
 * `@SomeAnnotation('foo', "bar", 123, -.42)`
 */
class Annotations {
    
    /**
     * Finds and parses all annotations present in the given doc string.
     * 
     * @param $object The object to process annotations for
     * @param $item The annotated item
     * @param string $doc The doc-comment attached to the item
     */
    public static function parseAll($object, $item, string $doc): array {
        $beginRegex = '/@(([a-zA-Z_-][a-zA-Z0-9_-]+)(\\\([a-zA-Z_-][a-zA-Z0-9_-]+))*)\(/';
        $valueRegex = '/((-?(\d*\.)?\d+)|(\'(.*?)\')|("(.*?)")|true|false)\s*(,\s*)?/';
        
        $all = [];

        // Find all opening expressions: "@Annotation("
        if(preg_match_all($beginRegex, $doc, $matches, \PREG_OFFSET_CAPTURE | \PREG_SET_ORDER)) {
            foreach($matches as $match) {
                $params = [];
                $className = $match[1][0];
                $offset = $match[0][1] + strlen($match[0][0]);
            
                $end = false;
                while(!$end) {
                    if(!preg_match($valueRegex, $doc, $valueMatch, 0, $offset)) break;

                    if($valueMatch[4] !== '' || $valueMatch[6] !== '') {  // string
                        $params[] = $valueMatch[5];
                    } else if($valueMatch[2] !== '' && $valueMatch[3] === '') {  // int
                        $params[] = (int)$valueMatch[2];
                    } else if($valueMatch[2] !== '' && $valueMatch[3] !== '') {  // float
                        $params[] = (float)$valueMatch[2];
                    } else if($valueMatch[1] === 'true') {  // true
                        $params[] = true;
                    } else if($valueMatch[1] === 'false') {  // false
                        $params[] = false;
                    }

                    $offset += strlen($valueMatch[0]);
                    if(substr($doc, $offset, 1) === ')') $end = true;
                }

                $class = new \ReflectionClass($className);
                if(!$class->isSubclassOf('Meta\Annotation')) {
                    throw new AnnotationException('Type '.$className.' is not a subclass of Meta\Annotation');
                }

                $all[] = $className::instantiate($object, $item, $params);
            }
        }

        return $all;
    }

}