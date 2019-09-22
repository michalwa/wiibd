<?php

namespace Routing;

use Http\Request;
use Http\Response;

/**
 * A router endpoint that handles specific requests
 */
abstract class Route {

    /**
     * The name of this route (debugging purposes)
     * @var string
     */
    private $name;

    /**
     * Constructs a new `Route` object
     * 
     * @param string $name The name for this route
     */
    public function __construct(string $name = '<route>') {
        $this->name = $name;
    }

    /**
     * Returns a textual representation of this route
     */
    public function __toString(): string {
        return $this->name;
    }

    /**
     * The name of this route (debugging purposes)
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Handles the given request and returns a response or `null`.
     * 
     * @param \App $app The app
     * @param Request $request The request to handle
     */
    public abstract function tryHandle(\App $app, Request $request): ?Response;

}
