<?php

namespace Database\Query;

/**
 * An INSERT query
 */
class Insert extends Query {

    /**
     * The table to insert into
     * @var string
     */
    private $tableName;

    /**
     * The column values to insert
     */
    private $record;

    /**
     * Constructs an INSERT query
     *
     * @param array $record Column names associated with the values to insert
     */
    public function __construct($record) {
        $this->record = $record;
    }

    /**
     * Sets the table to which to insert the record
     */
    public function into(string $tableName): self {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function build(QueryParams $params): string {
        return 'INSERT INTO'
            .' '.$this->tableName
            .' ('.implode(', ', array_keys($this->record)).')'
            .' VALUES ('.$params->addAll(array_values($this->record)).')';
    }

}
