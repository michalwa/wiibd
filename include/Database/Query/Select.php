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
     * Constructs a SELECT query
     *
     * @param string[] $fields The fields to select
     */
    public function __construct($fields = ['*']) {
        $this->fields = $fields;
    }

    /**
     * Builds and returns the string to be interpolated into the query after the `SELECT` keyword
     */
    private function fieldsString(): string {
        if($this->fields === [] || $this->fields[0] === '*') {
            return '*';
        }
        $str = '';
        foreach($this->fields as $field) {
            if($str !== '') $str .= ', ';
            $str .= '`'.$field.'`';
        }
        return $str;
    }

    /**
     * {@inheritDoc}
     */
    protected function build(QueryParams $params): string {
        $where = $this->whereClause($params);

        return 'SELECT '.$this->fieldsString()
            .' FROM '.$this->tableName
            .($where !== '' ? ' WHERE '.$where : '');
    }

}
