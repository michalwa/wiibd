<?php

namespace Database\Query;

/**
 * A JOIN clause in a SELECT query
 */
class Join {

    /**
     * Join type (`''`, `'INNER'`, `'OUTER'`, `'LEFT'`, `'RIGHT'`)
     * @var string
     */
    private $type;

    /**
     * Right-hand table name
     * @var string
     */
    private $rightTableName;

    /**
     * Foreign key column in the right-hand table
     */
    private $foreignKeyColumnName;

    /**
     * Constructs a JOIN clause object
     *
     * @param string $type Join type (`''`, `'INNER'`, `'OUTER'`, `'LEFT'`, `'RIGHT'`)
     * @param string $rightTableName The right-hand table name
     * @param string $foreignKeyColumnName The column in the right-hand table referencing
     *                                     primary keys of the left-hand table
     */
    public function __construct(
        string $type,
        string $rightTableName,
        string $foreignKeyColumnName
    ) {
        $this->type = $type;
        $this->rightTableName = $rightTableName;
        $this->foreignKeyColumnName = $foreignKeyColumnName;
    }

    /**
     * Builds the JOIN clause into an SQL string
     *
     * @param string $leftTableName The left-hand table name (the one being joined to)
     */
    public function build(string $leftTableName): string {
        return ($this->type === '' ? '' : $this->type.' ').'JOIN'
            .' '.$this->rightTableName
            .' ON '.$this->rightTableName.'.'.$this->foreignKeyColumnName
            .' = '.$leftTableName.'.id';
    }

}
