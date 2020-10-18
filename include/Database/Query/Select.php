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
    public function join(string $type, string $table, string $foreignKey): self {
        $this->joins[] = new Join($type, $table, $foreignKey);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function build(QueryParams $params): string {
        $fields = implode(', ', $this->fields);
        $where = $this->whereClause($params);
        $join = implode(' ', array_map(fn($j) => $j->build($this->tableName), $this->joins));

        return 'SELECT '.$fields
            .' FROM '.$this->tableName
            .($join !== '' ? ' '.$join : '')
            .($where !== '' ? ' WHERE '.$where : '');
    }

}
