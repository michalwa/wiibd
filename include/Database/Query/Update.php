<?php

namespace Database\Query;

use Database\Result;

/**
 * An UPDATE query
 */
class Update extends TableQuery {

    /**
     * The values to update
     */
    private $record = [];

    /**
     * Constructs an UPDATE query
     *
     * @param string $tableName The name of the table to update
     */
    public function __construct(string $tableName) {
        $this->from($tableName);
    }

    /**
     * Sets the value to set for the specified column
     *
     * @param string $column The column to set
     * @param mixed $value The value to set
     *
     * @return self for chaining
     */
    public function set(string $column, $value): self {
        $this->record[$column] = $value;
        return $this;
    }

    /**
     * Sets the values given in the associative array as column names
     * associated with values
     *
     * @param array $values The column values to set
     *
     * @return self for chaining
     */
    public function setAll(array $values): self {
        $this->record = array_merge($this->record, $values);
        return $this;
    }

    /**
     * Removes the specified column from the query
     *
     * @param string $column The column to remove from the query
     *
     * @return self for chaining
     */
    public function except(string $column): self {
        unset($this->record[$column]);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function build(QueryParams $params): string {

        $sets = array_map_assoc(
            fn($col, $val) => $col.' = '.$params->add($val),
            $this->record);

        $set = implode(', ', $sets);

        $where = $this->whereClause($params);

        return 'UPDATE'
            .' '.$this->tableName
            .' SET '.$set
            .($where !== '' ? ' WHERE '.$where : '');
    }

}
