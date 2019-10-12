<?php

namespace Database;

use \Config;
use \PDO;
use \PDOException;
use Database\Query\Select;
use Database\Query\Update;
use Database\Query\Delete;
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
     * Submits the given query to the database and returns the result
     */
    public function query(string $query): Result {
        if(!$this->isConnected()) $this->connect();
        $stmt = $this->pdo->query($query);
        return new Result($query, $stmt);
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
        throw new DatabaseException('Database not initialized.');
    }

    /**
     * Returns a SELECT query object associated with this database
     * 
     * @param string[] $fields The fields to select
     */
    public static function select($fields = ['*']): Select {
        return new Select($fields);
    }

    /**
     * Returns a UPDATE query object associated with this database
     * 
     * @param string $tableName The name of the table to update
     */
    public static function update(string $tableName): Update {
        return new Update($tableName);
    }

    /**
     * Returns a DELETE query object associated with this database
     */
    public static function delete(): Delete {
        return new Delete();
    }

}
