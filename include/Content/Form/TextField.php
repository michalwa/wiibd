<?php

namespace Content\Form;

use Http\Request;

/**
 * Represents an &lt;input type="text"&gt; element
 */
class TextField implements Field {

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
     * Constructs a text input field
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
            return $request->getQuery($this->name);

        return $request->getPost($this->name);
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(Request $request, string $method) {
        return !$this->required || $this->getValue($request, $method) !== null;
    }

    /**
     * {@inheritDoc}
     */
    public function html(array $params = [], string $style = 'default'): string {
        return Form::loadTemplate($style, 'text-field')
            ->render(array_merge([
                'name' => $this->name,
                'required' => $this->required,
            ], $this->params, $params));
    }

}
