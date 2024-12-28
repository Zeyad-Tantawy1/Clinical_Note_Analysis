<?php
define('GOOGLE_API_KEY', 'AIzaSyD5vGPPA5E509JfnnxTXlNFDiR8ejewdEg');

if (!class_exists('Database'))
{
    class Database {
        private static $instance = null;
        private $conn;
        private function __construct() {
            $host = 'localhost';
            $user = 'hoho';
            $password = '1234';
            $dbname = 'project';
            $this->conn = new mysqli($host, $user, $password, $dbname);
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
        private function __clone() {}
        public function __wakeup() {
            throw new Exception("Cannot unserialize a singleton.");
        }
    }
}

?>
