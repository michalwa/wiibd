<?php

namespace Content\Form;

use Http\Request;

class SelectField implements Field {

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var bool
     */
    private $multiple;

    /**
     * @var array
     */
    private $params;

    /**
     * Constructs an option select field
     */
    public function __construct(
        string $name,
        array $options,
        bool $required = false,
        bool $multiple = false,
        array $params = []
    ) {
        $this->name = $name;
        $this->options = $options;
        $this->required = $required;
        $this->multiple = $multiple;
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
        $value = $method === 'GET'
            ? $request->getQuery($this->name)
            : $request->getPost($this->name);

        if($this->multiple) {
            if(!is_array($value)) return null;

            $options = array_keys($this->options);
            return array_filter($value, fn($v) => in_array($v, $options));
        }

        return in_array($value, array_keys($this->options)) ? $value : null;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(Request $request, string $method): bool {
        return $this->getValue($request, $method) !== null;
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
        return Form::loadTemplate($style, 'select-field')
            ->render(array_merge([
                'valid' => $this->isValid($request, $method),
                'value' => $this->getValue($request, $method),
                'name' => $this->name . ($this->multiple ? '[]' : ''),
                'options' => $this->options,
                'required' => $this->required,
                'multiple' => $this->multiple,
            ], $this->params, $params));
    }

}
