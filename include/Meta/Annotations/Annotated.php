<?php

namespace Meta\Annotations;

/**
 * An annotated reflection. To be used on `Reflection...Annotated` classes
 */
trait Annotated {

    /**
     * Annotations attached to this item
     */
    private $annotations = [];

    /**
     * Returns all annotations attached to this item. If `$className` is passed,
     * returns all annotations of the specified class.
     * 
     * @param null|string $className The name of the annotation class
     * 
     * @return Annotation[]
     */
    public function getAnnotations(?string $className = null) {
        if($className === null) return $this->annotations;

        $annotations = [];
        foreach($this->annotations as $annotation) {
            if(get_class($annotation) === $className) {
                $annotations[] = $annotation;
            }
        }
        return $annotations;
    }

    /**
     * Returns a single instance of the specified annotation class attached to this item
     * or `null` if it is not present.
     * 
     * @param string $className The name of the annotation class
     * 
     * @return null|Annotation
     */
    public function getAnnotation(string $className): ?Annotation {
        $annotations = $this->getAnnotations($className);
        return count($annotations) > 0 ? $annotations[0] : null;
    }

}
