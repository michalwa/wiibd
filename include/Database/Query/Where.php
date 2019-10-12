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
     * Constructs a where condition
     * 
     * @param string $column The column to test
     * @param string $operator The operator to use for the test
     * @param mixed $operand The second operand
     */
    public function __construct(string $column, string $operator = '=', $operand = true) {
        $this->column   = $column;
        $this->operator = $operator;
        $this->operand  = $operand;
    }

    /**
     * Builds this condition into a string
     */
    public function __toString(): string {
        return '`'.$this->column.'` '.$this->operator.' '.$this->operand;
    }

    /**
     * Joins the given `WHERE` conditions and logical operators in to a `WHERE` clause string
     * 
     * @param Where[] $conditions The conditons to join
     * @param string[] $operators The logical operators to join the conditions with
     */
    public static function buildClause(iterable $conditions, iterable $operators): string {
        $str = '';
        if(count($operators) < count($conditions) - 1) {
            throw new InvalidArgumentException("Not enough logical operators to join.");
        }
        for($i = 0; $i < count($conditions); $i++) {
            if($i > 0) {
                $str .= ' '.$operators[$i - 1].' ';
            }
            $str .= $conditions[$i];
        }
        return $str;
    }

}