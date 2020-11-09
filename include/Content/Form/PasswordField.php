<?php

namespace Content\Form;

use Http\Request;

/**
 * Represents an &lt;input type="text"&gt; element
 */
class PasswordField implements Field {

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;

    /**
     * Constructs a text input field
     */
    public function __construct(string $name, array $params = []) {
        $this->name = $name;
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
    public function html(array $params = [], string $style = 'default'): string {
        return Form::loadTemplate($style, 'password-field')
            ->render(array_merge([
                'name' => $this->name,
            ], $this->params, $params));
    }

}
