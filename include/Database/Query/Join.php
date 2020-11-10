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
     * Left-hand table name
     * @var string
     */
    private $leftTableName;

    /**
     * Right-hand table name
     * @var string
     */
    private $rightTableName;

    /**
     * Key column in the left-hand table
     */
    private $leftKey;

    /**
     * Key column in the right-hand table
     */
    private $rightKey;

    /**
     * Constructs a JOIN clause object
     *
     * @param string $type Join type (`''`, `'INNER'`, `'OUTER'`, `'LEFT'`, `'RIGHT'`)
     */
    public function __construct(
        string $type,
        string $leftTableName,
        string $rightTableName,
        string $leftKey,
        string $rightKey
    ) {
        $this->type = $type;
        $this->leftTableName = $leftTableName;
        $this->rightTableName = $rightTableName;
        $this->leftKey = $leftKey;
        $this->rightKey = $rightKey;
    }

    /**
     * Builds the JOIN clause into an SQL string
     *
     * @param string $leftTableName The left-hand table name (the one being joined to)
     */
    public function build(): string {
        return ($this->type === '' ? '' : $this->type.' ').'JOIN'
            .' '.$this->rightTableName
            .' ON '.$this->leftTableName.'.'.$this->leftKey
            .' = '.$this->rightTableName.'.'.$this->rightKey;
    }

}
