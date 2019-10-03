<?php

namespace Routing;

use Http\Request;
use Http\Response;

/**
 * A router endpoint that handles specific requests
 */
abstract class Route {

    /**
     * The name of this route
     * @var string
     */
    private $name = '<route>';

    /**
     * Returns a textual representation of this route
     */
    public function __toString(): string {
        return $this->name;
    }

    /**
     * Sets the name of this route
     * 
     * @param string $name The new name of this route
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * Returns the name of this route
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Handles the given request and returns a response or `null`.
     * 
     * @param Request $request The request to handle
     */
    public abstract function tryHandle(Request $request): ?Response;

}
