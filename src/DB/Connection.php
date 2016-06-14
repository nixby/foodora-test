<?php

namespace FoodoraTest\DB;

/**
 * Connection is a helper class
 * that established a new PDO connection to the database
 * @package   FoodoraTest\DB
 * @version   0.0.1
 * @license   MIT
 */
class Connection
{
    /**
     * @var string $db database name to connect to
     */
    private $db;

    /**
     * @var string $host hostname of the database
     */
    private $host;

    /**
     * @var string $username username to connect with
     */
    private $username;

    /**
     * @var string $password password of the user
     */
    private $password;

    /**
     * @var string $connection established connection to the db
     */
    private $connection;

    /**
     * Class constructor. Saves all necessary
     * information to establish new DB-Connection.
     * @param string $host hostname.
     * @param string $db database name.
     * @param string $username username.
     * @param string $password users password.
     * @return void
     */
    public function __construct($host, $db, $username, $password)
    {
        $this->host = $host;
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;
    }
    
    /**
     * Establishes new connection to the database.
     * @param void
     * @return \PDO PDO instance representing a connection to a database.
     */
    public function connect()
    {
        $connectionString = 'mysql:host=' . $this->host . ';dbname=' . $this->db . ';charset=UTF8';
        $this->connection = new \PDO($connectionString, $this->username, $this->password);
        return $this->connection;
    }

    /**
     * Sets the current DB-Connection to null.
     * @param void.
     * @return void
     */
    public function disconnect()
    {
        $this->connection = null;
    }
}
