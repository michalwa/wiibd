<?php

namespace Content\Form;

use Http\Methods;
use Http\Request;
use InvalidArgumentException;
use Utils\NotFoundException;
use View\View;

/**
 * Abstraction over an HTML form
 */
class Form {

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $action;

    /**
     * @var Field[]
     */
    private $fields = [];

    /**
     * Constructs a new form with the specified method
     */
    public function __construct(string $method = 'GET', string $action = '#') {
        $method = strtoupper($method);
        if(!in_array($method, Methods::METHODS))
            throw new InvalidArgumentException("Invalid form method: $method");

        $this->method = $method;
        $this->action = $action;
    }

    /**
     * Adds a field to the form
     *
     * @param Field $field The field to add
     */
    public function addField(Field $field): self {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Returns the value of the field with the specified name
     *
     * @param string $fieldName The name of the field to return the value of
     */
    public function getValue(string $fieldName, ?Request $request = null) {
        $request ??= Request::get();

        foreach($this->fields as $field) {
            if($field->getName() === $fieldName)
                return $field->getValue($request, $this->method);
        }

        throw new InvalidArgumentException("No value for field $fieldName");
    }

    /**
     * Returns the form values as an associative array
     */
    public function getValues(?Request $request = null): array {
        $request ??= Request::get();

        $values = [];
        foreach($this->fields as $field) {
            $values[$field->getName()] = $field->getValue($request, $this->method);
        }
        return $values;
    }

    /**
     * Builds and returns the HTML form
     *
     * @param array $params Additional parameters to pass to the template
     */
    public function html(array $params = [], string $style = 'default'): string {
        $fields = '';
        foreach($this->fields as $field) {
            $fields .= $field->html($params, $style);
        }

        return self::loadTemplate($style, 'form')
            ->render(array_merge($params, [
                'fields' => $fields,
                'action' => $this->action,
                'method' => strtolower($this->method),
            ]));
    }

    /**
     * Tries to load a form template with the given style and name.
     * Falls back to the default style if the specified style doesn't override
     * the template with the given name.
     */
    public static function loadTemplate(string $style, string $name): View {
        try {
            return View::load("_form/$style/$name");
        } catch(NotFoundException $e) {
            return View::load("_form/default/$name");
        }
    }

}
