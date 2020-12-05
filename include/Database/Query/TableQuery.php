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
     * `WHERE` clause root
     * @var null|Where
     */
    protected $where = null;

    /**
     * `WHERE` clause current node
     * @var null|Where
     */
    private $whereCurrent = null;

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
     * @param string $_join The logical operator to join this `WHERE` condition with previous conditions
     *
     * @return self for chaining
     */
    public function where(string $column, ?string $operator = null, $operand = null, string $_join = 'AND'): self {
        $where = new Where($column, $operator, $operand);

        if($this->where === null) {
            $this->where = $this->whereCurrent = $where;
        } else {
            $this->whereCurrent = $this->whereCurrent->append($_join, $where);
        }
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
    public function and(string $column, ?string $operator = null, $operand = null): self {
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
    public function or(string $column, ?string $operator = null, $operand = null): self {
        return $this->where($column, $operator, $operand, 'OR');
    }

}
