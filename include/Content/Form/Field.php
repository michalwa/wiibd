<?php

namespace Content\Form;

use Http\Request;

/**
 * A field in an HTML form
 */
interface Field {

    /**
     * Returns the name of the field
     */
    public function getName(): string;

    /**
     * Extracts the value of the field from the given request.
     * Returns `null` if the value is not present in the request.
     */
    public function getValue(Request $request, string $method);

    /**
     * Tells whether the field can be used correctly based on the given request
     */
    public function isValid(Request $request, string $method);

    /**
     * Builds and returns the HTML for the field
     *
     * @param array $params Additional parameters to pass to the view
     */
    public function html(array $params = [], string $style = 'default'): string;

}
