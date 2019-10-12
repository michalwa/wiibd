<?php

namespace Database\Query;

/**
 * Base class for table queries that use the `FROM` table specifier and `WHERE` clauses
 */
abstract class TableQuery extends Query {

    /**
     * The table name
     * @var string
     */
    protected $tableName;

    /**
     * `WHERE` conditions
     * @var Where[]
     */
    private $where = [];

    /**
     * Logic operators for joining `WHERE` conditions
     * @var string[]
     */
    private $whereOps = [];

    /**
     * Builds and returns the `WHERE` clause for this query
     */
    protected function whereClause(): string {
        return Where::buildClause($this->where, $this->whereOps);
    }

    /**
     * Sets the table name
     * 
     * @param string $tableName The name of the table to query
     * 
     * @return self for chaining
     */
    public function from(string $tableName): self {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Adds a `WHERE` condition to this query
     * 
     * @param string $column The column to test
     * @param string $operator The operator to use for the test
     * @param mixed $operand The second operand
     * @param string $join The logical operator to join this `WHERE` condition with previous conditions
     * 
     * @return self for chaining
     */
    public function where(string $column, string $operator = '=', $operand = true, string $join = 'AND'): self {
        if(count($this->where) > 0) $this->whereOps[] = $join;
        $this->where[] = new Where($column, $operator, $operand);
        return $this;
    }

    /**
     * Appends a `WHERE` condition preceded with an `AND` operator
     * 
     * @param string $column The column to test
     * @param string $operator The operator to use for the test
     * @param mixed $operand The second operand
     * 
     * @return self for chaining
     */
    public function and(string $column, string $operator = '=', $operand = true): self {
        return $this->where($column, $operator, $operand, 'AND');
    }

    /**
     * Appends a `WHERE` condition preceded with an `OR` operator
     * 
     * @param string $column The column to test
     * @param string $operator The operator to use for the test
     * @param mixed $operand The second operand
     * 
     * @return self for chaining
     */
    public function or(string $column, string $operator = '=', $operand = true): self {
        return $this->where($column, $operator, $operand, 'OR');
    }

}
