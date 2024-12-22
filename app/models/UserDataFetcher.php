<?php
require_once('../app/config/connect_DB.php');

class UserDataFetcher {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchUserIds() {
        $sql = 'SELECT id FROM users';
        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            return $data;
        }
        return [];
    }
}
?>