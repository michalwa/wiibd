<?php

namespace Content\Form;

use Http\Request;

/**
 * Represents an &lt;input type="text"&gt; field
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
            return trim($request->getQuery($this->name));

        return trim($request->getPost($this->name));
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(Request $request, string $method): bool {
        if(!$this->required) return true;
        if(($value = $this->getValue($request, $method)) === null) return false;

        // Make sure the string is not all spaces
        foreach(str_split($value) as $char) {
            if(!ctype_space($char)) return true;
        }
        return false;
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
        return Form::loadTemplate($style, 'text-field')
            ->render(array_merge([
                'valid' => $this->isValid($request, $method),
                'value' => $this->getValue($request, $method),
                'name' => $this->name,
                'required' => $this->required,
            ], $this->params, $params));
    }

}
