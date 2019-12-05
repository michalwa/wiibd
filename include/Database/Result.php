<?php

namespace Database;

use \Iterator;
use \PDO;
use \PDOStatement;

/**
 * Represents a result fetched from the database
 */
class Result implements Iterator {

    /**
     * The query string used to fetch this result
     * @var string
     */
    private $queryString;

    /**
     * Whether the query used to fetch this result was successful
     * @var bool
     */
    private $success;

   /**
    * The statement
    * @var null|PDOStatement
    */
    private $stmt;

    /**
     * The number of fetched rows
     * @var int
     */
    private $numRows;

    /**
     * The current row index
     * @var int
     */
    private $row = 0;

    /**
     * Cached rows
     * @var array[]
     */
    private $cachedRows = [];

    /**
     * Constructs a new `Result` object
     * 
     * @param string $queryString The query string used to fetch this result
     * @param PDOStatement|bool $stmt The result from calling `PDO::query()`
     */
    public function __construct(string $queryString, $stmt) {
        $this->queryString = $queryString;
        $this->success = $stmt !== false && $stmt->errorCode() === PDO::ERR_NONE;
        $this->stmt = $this->success ? $stmt : null;
        $this->numRows = $this->success ? $stmt->rowCount() : 0;
    }

    /**
     * Returns the query string used to fetch this result
     */
    public function getQueryString(): string {
        return $this->queryString;
    }

    /**
     * Returns the number of rows this result has
     */
    public function getNumRows(): int {
        return $this->numRows;
    }

    /**
     * Returns `true` if the query used to fetch this result was successful,
     * `false` otherwise.
     */
    public function ok(): bool {
        return $this->success;
    }

    /**
     * Returns the single row of this result. If this result is not successful, `null` is returned.
     * If this result has more than one row, an exception is thrown.
     * 
     * @throws DatabaseException If this result has more than one row
     */
    public function get() {
        $this->rewind();
        return $this->current();
    }

    /**
     * {@inheritDoc}
     */
    public function rewind() {
        $this->row = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function current() {
        if(!$this->success) return null;
        $missingRows = $this->row - count($this->cachedRows) + 1;
        while($missingRows > 0) {
            $this->cachedRows[] = $this->stmt->fetch(PDO::FETCH_ASSOC);
            $missingRows--;
        }
        return $this->cachedRows[$this->row];
    }

    /**
     * {@inheritDoc}
     */
    public function key(): ?int {
        if(!$this->success) return null;
        return $this->row;
    }

    /**
     * {@inheritDoc}
     */
    public function next() {
        $this->row++;
    }

    /**
     * {@inheritDoc}
     */
    public function valid(): bool {
        return $this->row < $this->numRows;
    }

    /**
     * Returns an empty result with success status `false`
     */
    public static function fail(): Result {
        return new Result('', false);
    }

}
