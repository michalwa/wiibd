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
    private $columnValues;

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
        return $this;
    }

    /**
     * Returns a string to be interpolated into the final query after the `SET` keyword
     */
    private function setString(): string {
        $str = '';
        foreach($this->columnValues as $column => $value) {
            if($str !== '') $str .= ', ';
            $str .= '`'.$column.'`'.'='.$value;
        }
        return $str;
    }

    /**
     * {@inheritDoc}
     */
    protected function build(): string {
        if(($set = $this->setString()) === '') {
            return 'SELECT 1';
        }
        $where = $this->whereClause();

        return 'UPDATE'.'`'.$this->tableName.'`'
            .' SET '.$set
            .($where !== '' ? ' WHERE '.$where : '');
    }

}
