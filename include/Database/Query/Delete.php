<?php

namespace Database\Query;

/**
 * A DELETE query
 */
class Delete extends TableQuery {

    /**
     * {@inheritDoc}
     */
    protected function build(): string {
        $where = $this->whereClause();

        return 'DELETE'
            .' FROM `'.$this->tableName.'`'
            .($where !== '' ? ' WHERE '.$where : '');
    }

}
