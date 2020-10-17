<?php

namespace Database\Query;

/**
 * A DELETE query
 */
class Delete extends TableQuery {

    /**
     * {@inheritDoc}
     */
    public function build(QueryParams $params): string {
        $where = $this->whereClause($params);

        return 'DELETE FROM'
            .' '.$this->tableName
            .($where !== '' ? ' WHERE '.$where : '');
    }

}
