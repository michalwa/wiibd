<?php

namespace Meta\Annotations;

use \Reflector;

/**
 * Occurs when an annotation is used in an invalid way
 */
class AnnotationException extends \Exception {

    /**
     * @param string $message The message
     * @param Reflector $item The annotated item
     * @param int $lineOffset Line offset pointing to the line where the annotation was declared
     */
    public function __construct(string $message = "", Reflector $item = null, int $lineOffset = 0) {
        parent::__construct($message);
        if(method_exists($item, 'getFileName')) $this->file = $item->getFileName();
        if(method_exists($item, 'getStartLine')) $this->line = $item->getStartLine() + $lineOffset;
    }

}
