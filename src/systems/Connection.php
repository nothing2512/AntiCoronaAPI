<?php

namespace Tour\systems;

use PDO;
use PDOStatement;
use Tour\config\Database;

/**
 * Class Connection
 * @package Tour\systems
 */
class Connection implements Database
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var PDOStatement
     */
    private $stmt;

    /**
     * Connection constructor.
     */
    public function __construct() {

        // Create connection
        $this->_create();
    }

    /**
     * Create Connection
     */
    private function _create() {

        // get host & dbname
        $host = self::HOST;
        $db = self::DB;

        // Create connection
        $pdo = new PDO("mysql:host=$host;dbname=$db", self::USER, self::PASS);

        // Set PDO attribute
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Save pdo connection
        $this->pdo = $pdo;
    }

    /**
     * Parsing data
     *
     * @param $object
     * @param $data
     * @param int $i
     * @return object|null
     */
    private function _parse ( $object, $data, $i = 0 ) {

        // Check single data
        if ( !isset($data[0]) ) return $object($data);

        // Parsing data
        $data[$i] = $object($data[$i]);

        // Make loop
        if ( $i < sizeof($data) - 1 ) return $this->_parse($object, $data, $i + 1);

        return $data;
    }

    /**
     * Fetching data
     *
     * @param $data
     * @param null $object
     * @return object|null
     */
    private function _fetch( $data, $object = null) {

        // Closing connection
        $this->_close();

        // Parsing and returning data
        return $object != null ? $this->_parse($object, $data) : $data;
    }

    /**
     * Executing query
     *
     * @param $query
     * @param array $params
     */
    public function query ( $query, $params = []) {

        // Check connection
        if ( is_null($this->pdo)) $this->_create();

        // Preparing statement
        $stmt = $this->pdo->prepare($query);

        // Executing statement
        $stmt->execute($params);

        // Save statements
        $this->stmt = $stmt;
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * Fetching single data
     *
     * @param null $object
     * @return object|null
     */
    public function fetch ($object = null ) {

        // Fetching data
        $data = $this->stmt->fetch();

        // Check avaibility data
        if ($data == false) return null;

        return ( object ) $this->_fetch($data, $object);
    }

    /**
     * Fetching multiple data
     *
     * @param null $object
     * @return array|null
     */
    public function fetchAll ( $object = null ) {

        // Fetching data
        $data = $this->stmt->fetchAll();

        // Check avaibility data
        if ($data == false) return null;

        return ( array ) $this->_fetch($data, $object);
    }

    /**
     * Closing connection
     */
    private function _close() {

        $this->pdo = null;
        $this->stmt = null;
    }
}