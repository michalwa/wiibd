<?php

namespace Content\Form;

use Http\Request;

/**
 * Represents an &lt;input type="number"&gt; element
 */
class NumberField implements Field {

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var array
     */
    private $params;

    /**
     * Constructs a number input field
     */
    public function __construct(string $name, bool $required = false, array $params = []) {
        $this->name = $name;
        $this->required = $required;
        $this->params = $params;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(Request $request, string $method) {
        if($method === 'GET')
            return (int)$request->getQuery($this->name);

        return (int)$request->getPost($this->name);
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(Request $request, string $method): bool {
        return !$this->required || $this->getValue($request, $method) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function html(
        Request $request,
        string $method,
        array $params = [],
        string $style = 'default'
    ): string {
        return Form::loadTemplate($style, 'number-field')
            ->render(array_merge([
                'valid' => $this->isValid($request, $method),
                'value' => $this->getValue($request, $method),
                'name' => $this->name,
                'required' => $this->required,
            ], $this->params, $params));
    }

}
