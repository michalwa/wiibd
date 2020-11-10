<?php

namespace Database\Query;

/**
 * A SELECT query
 */
class Select extends TableQuery {

    /**
     * The fields to select
     * @var string[]
     */
    private $fields;

    /**
     * The appended join clauses
     * @var Join[]
     */
    private $joins = [];

    /**
     * ORDER BY column name
     * @var null|string
     */
    private $orderBy = null;

    /**
     * ORDER BY orientation
     * @var string
     */
    private $orderDir = 'ASC';

    /**
     * Constructs a SELECT query
     *
     * @param string[] $fields The fields to select
     */
    public function __construct(...$fields) {
        $this->fields = $fields;

        if($fields === [] || $fields[0] === '*') {
            $this->fields = ['*'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function from(string $tableName): Select {
        return parent::from($tableName);
    }

    /**
     * Adds a join to this query
     *
     * @param string $type Join type (`''`, `'INNER'`, `'OUTER'`, `'LEFT'`, `'RIGHT'`)
     * @param string $table The table to join
     * @param string $foreignKey The column in the specified table referencing primary keys
     *                           of the selected table
     *
     * @return self for chaining
     */
    public function join(string $type, string $rightTable, string $leftKey, string $rightKey = 'id', ?string $leftTable = null): self {
        $leftTable ??= $this->tableName;
        $this->joins[] = new Join($type, $leftTable, $rightTable, $leftKey, $rightKey);
        return $this;
    }

    /**
     * Sets the result to be orderered by the specified column.
     *
     * @param string $columnName The column to order the results records by
     * @param string $dir The direction in which to order the records
     *
     * @return self for chaining
     */
    public function orderBy(string $columnName, string $dir = 'ASC'): self {
        $this->orderBy = $columnName;
        $this->orderDir = $dir;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function build(QueryParams $params): string {
        $fields = implode(', ', $this->fields);
        $where = $this->whereClause($params);
        $join = implode(' ', array_map(fn($j) => $j->build(), $this->joins));

        return 'SELECT '.$fields
            .' FROM '.$this->tableName
            .($join !== '' ? ' '.$join : '')
            .($where !== '' ? ' WHERE '.$where : '')
            .($this->orderBy !== null ? ' ORDER BY '.$this->orderBy.' '.$this->orderDir : '');
    }

}
