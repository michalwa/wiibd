<?php

namespace Database\Query;

use Database\Database;
use Database\Result;

/**
 * A generic database query
 */
abstract class Query {

    /**
     * Builds the query into an SQL string
     */
    protected abstract function build(): string;

    /**
     * Submits the query to the database
     */
    public function execute(): Result {
        return Database::get()->query($this->build());
    }

}
