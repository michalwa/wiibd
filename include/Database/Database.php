<?php

namespace Database;

use \Config;
use \PDO;
use \PDOException;
use Database\Query\Select;
use Database\Query\Update;
use Database\Query\Delete;
use Database\Query\Insert;
use Database\Query\QueryParams;
use Exception;

/**
 * Main database interface
 */
class Database {

    /**
     * DSN string formats for different database types
     */
    private const DSN_FORMAT = [
        'cubrid' => 'cubrid:host=%s;port=%d;dbname=%s',
        'dblib'  => 'dblib:host=%s:%d;dbname=%s',
        'mssql'  => 'sqlsrv:Server=%s,%d;Database=%s',
        'mysql'  => 'mysql:host=%s;port=%d;dbname=%s',
        'pgsql'  => 'pgsql:host=%s;port=%d;dbname=%s',
        'sqlite' => 'sqlite::memory:'
    ];

    /**
     * The singleton `Database` instance
     * @var Database
     */
    private static $instance;

    /**
     * The DSN string
     * @var string
     */
    private $dsn;

    /**
     * The database user name
     * @var string
     */
    private $username;

    /**
     * The database password
     * @var string
     */
    private $password;

    /**
     * The constructed PDO instance
     * @var PDO
     */
    private $pdo = null;

    /**
     * Constructs a new `Database` instance
     *
     * @param string $type one of `cubrid`, `dblib`, `mssql`, `mysql`, `pgsql`, `sqlite`
     * @param string $host The database server host
     * @param int $port The port
     * @param string $name The name of the database
     * @param string $username The database user name
     * @param string $password The database user password
     */
    private function __construct(
        string $type,
        string $host,
        int    $port,
        string $name,
        string $username,
        string $password
    ) {
        $this->dsn = sprintf(self::DSN_FORMAT[$type], $host, $port, $name);
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Connects to the database
     *
     * @throws DatabaseException on failure
     */
    public function connect() {
        try {
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            throw new DatabaseException("Could not connect: ".$e->getMessage());
        }
    }

    /**
     * Checks if connected to the database
     */
    public function isConnected(): bool {
        if($this->pdo === null) return false;
        try {
            $this->pdo->query('SELECT 1 + 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Disconnects from the database
     */
    public function disconnect() {
        $this->pdo = null;
    }

    /**
     * Begins a transaction with the database
     */
    public function beginTransaction(): void {
        $this->pdo->beginTransaction();
    }

    /**
     * Commits the last transaction
     */
    public function commit(): void {
        $this->pdo->commit();
    }

    /**
     * Rolls back the last transaction
     */
    public function rollBack(): void {
        $this->pdo->rollBack();
    }

    /**
     * Submits the given query to the database and returns the result
     */
    public function query(string $query, QueryParams $params = null): Result {
        if(!$this->isConnected()) $this->connect();

        if($params === null) $params = new QueryParams();

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params->getValues());
        } catch(PDOException $e) {
            return new Result($stmt, $query, $params, $e->errorInfo[2]);
        }

        return new Result($stmt, $query, $params);
    }

    /**
     * Initializes the database using the given config
     */
    public static function init(Config $config) {
        if(self::$instance === null) {
            self::$instance = new self(
                $config->get('database.type'),
                $config->get('database.host'),
                $config->get('database.port'),
                $config->get('database.name'),
                $config->get('database.username'),
                $config->get('database.password')
            );
        }
    }

    /**
     * Returns the singleton instance of the `Database` class
     */
    public static function get(): Database {
        if(self::$instance !== null) {
            return self::$instance;
        }
        throw new DatabaseException("Database not initialized");
    }

    /**
     * Returns a new SELECT query object
     *
     * @param string[] $fields The fields to select
     */
    public static function select(...$fields): Select {
        return new Select(...$fields);
    }

    /**
     * Returns a new UPDATE query object
     *
     * @param string $tableName The name of the table to update
     */
    public static function update(string $tableName): Update {
        return new Update($tableName);
    }

    /**
     * Returns a new DELETE query object
     */
    public static function delete(): Delete {
        return new Delete();
    }

    /**
     * Returns a new INSERT query object
     *
     * @param array $record The record to insert
     */
    public static function insert(array $record): Insert {
        return new Insert($record);
    }

}
