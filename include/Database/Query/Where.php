<?php

namespace Database\Query;

use InvalidArgumentException;

/**
 * A `WHERE` condition object
 */
class Where {

    /**
     * The column to test
     * @var string
     */
    private $column;

    /**
     * The operator to use for the test
     * @var string
     */
    private $operator;

    /**
     * The second operand
     */
    private $operand;

    /**
     * @var null|self
     */
    private $next = null;

    /**
     * @var null|string
     */
    private $nextOperator = null;

    /**
     * Constructs a where condition
     *
     * @param string $column The column to test
     * @param null|string $operator The operator to use for the test
     * @param mixed $operand The second operand
     */
    public function __construct(string $column, ?string $operator = null, $operand = null) {
        $this->column   = $column;
        $this->operator = $operator;
        $this->operand  = $operand;
    }

    /**
     * Appends another where condition to this condition to be joined with
     * the specified logical operator.
     *
     * @return self $next for chaining
     */
    public function append(string $operator, self $next): self {
        $this->next = $next;
        $this->nextOperator = $operator;
        return $next;
    }

    /**
     * Builds this condition into a string and populates the query params with operands
     *
     * @param QueryParams $params The query params to populate
     */
    public function build(QueryParams $params): string {
        $str = '';

        if($this->operator === null) {
            $str = $this->column;
        } elseif($this->operand === null) {
            $str = $this->column.' '.$this->operator;
        } else {
            $operand = is_array($this->operand)
                ? '('.$params->addAll($this->operand).')'
                : $params->add($this->operand);

            $str = $this->column.' '.$this->operator.' '.$operand;
        }

        $str = ($this->next !== null
            ? '('.$str.' '.$this->nextOperator.' '.$this->next->build($params).')'
            : $str);

        return $str;
    }

}
