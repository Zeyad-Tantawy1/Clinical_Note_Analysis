<?php
if (!class_exists('Database')) {
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Database connection parameters
        $host = 'localhost';
        $user = 'hoho';
        $password = '1234';
        $dbname = 'project';

        // Create the database connection
        $this->conn = new mysqli($host, $user, $password, $dbname);

        // Check if connection is successful
        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserializing the instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
}
}
?>
