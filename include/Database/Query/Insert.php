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
    private $records;

    /**
     * Constructs an INSERT query
     *
     * @param mixed[string][] $records Column names associated with the values to insert
     */
    public function __construct(...$records) {
        $this->records = $records;
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
        $keys = [];
        foreach($this->records as $record) {
            array_append($keys, array_keys($record));
        }
        $keys = array_unique($keys);

        $allValues = [];
        foreach($this->records as $record) {
            $values = [];
            foreach($keys as $key) {
                $values[] = key_exists($key, $record) ? $record[$key] : null;
            }
            $allValues[] = $values;
        }

        $values = implode(', ', array_map(fn($v) => '('.$params->addAll($v).')', $allValues));

        return 'INSERT INTO'
            .' '.$this->tableName
            .' ('.implode(', ', $keys).')'
            .' VALUES '.$values;
    }

}
