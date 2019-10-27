<?php

namespace View;

use \Exception;
use \Throwable;

class TemplateException extends Exception {

    /**
     * Constructs a new template exception wrapper based on the given parameters
     * 
     * @param Throwable $e The throwable that occured in the template
     * @param null|string $file The template file name, if template was loaded from file
     */
    public function __construct(Throwable $e, ?string $file = null) {
        parent::__construct(get_class($e).': '.$e->getMessage(), 0, $e);
        $this->file = $file ?? '(view template)';
        $this->line = $e->getLine();
    }

}
