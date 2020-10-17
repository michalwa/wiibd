<?php

namespace Database\Query;

use Database\Database;
use Database\Result;

/**
 * A generic database query
 */
abstract class Query {

    /**
     * Builds the query into an SQL string and populates the given `QueryParams`
     * instance with parameters that need escaping (passed to prepared PDO statements as params)
     *
     * @param QueryParams $params The parameters to populate
     *
     * @return string The built query string
     */
    public abstract function build(QueryParams $params): string;

    /**
     * Submits the query to the database
     */
    public function execute(): Result {
        $params = new QueryParams();
        return Database::get()->query($this->build($params), $params);
    }

}
