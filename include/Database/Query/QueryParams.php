<?php

namespace Database\Query;

/**
 * Accumulates query parameters
 */
class QueryParams {

    /**
     * The accumulated values
     */
    private $values = [];

    /**
     * Adds the given value to the accumulated parameter values and returns a hole
     * to be interpolated into a PDO statement
     */
    public function add($param): string {
        array_push($this->values, $param);
        return '?';
    }

    /**
     * Adds all the given values to the accumulated parameter values and returns
     * comma-separated holes to be interpolated into a PDO statement
     */
    public function addAll($params): string {
        $this->values = array_merge($this->values, $params);
        return implode(', ', array_fill(0, count($params), '?'));
    }

    /**
     * Returns the accumulated values
     */
    public function getValues() {
        return $this->values;
    }

}
