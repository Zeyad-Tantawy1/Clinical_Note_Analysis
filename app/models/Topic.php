<?php
require_once('../app/config/connect_DB.php');

class Topic {
    private $conn;
    private $table = 'topics';

    // Topic properties
    public $id;
    public $title;
    public $description;
    public $user;
    public $replies;
    public $date;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Create new topic
    public function createTopic($title, $description, $username) {
        $sql = "INSERT INTO {$this->table} (title, description, user, date, replies) VALUES (?, ?, ?, NOW(), 0)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $title, $description, $username);
        
        $success = mysqli_stmt_execute($stmt);
        $topicId = $success ? mysqli_insert_id($this->conn) : null;
        mysqli_stmt_close($stmt);
        
        return $topicId;
    }

    // Get topic by ID
    public function getTopicById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Get all topics
    public function getAllTopics($limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY date DESC";
        if ($limit !== null) {
            $sql .= " LIMIT ?, ?";
        }
        
        $stmt = mysqli_prepare($this->conn, $sql);
        
        if ($limit !== null) {
            mysqli_stmt_bind_param($stmt, "ii", $offset, $limit);
        }
        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $topics = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $topics[] = $row;
        }
        return $topics;
    }

    // Get topics by user
    public function getTopicsByUser($username) {
        $sql = "SELECT * FROM {$this->table} WHERE user = ? ORDER BY date DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $topics = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $topics[] = $row;
        }
        return $topics;
    }

    // Update topic
    public function updateTopic($id, $title, $description) {
        $sql = "UPDATE {$this->table} SET title = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Delete topic
    public function deleteTopic($id) {
        // First delete all comments associated with this topic
        $sql = "DELETE FROM comments WHERE topic_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        // Then delete the topic
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Update replies count
    public function updateRepliesCount($id) {
        $sql = "UPDATE {$this->table} SET replies = (SELECT COUNT(*) FROM comments WHERE topic_id = ?) WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Search topics
    public function searchTopics($searchTerm) {
        $searchTerm = "%$searchTerm%";
        $sql = "SELECT * FROM {$this->table} WHERE title LIKE ? OR description LIKE ? ORDER BY date DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $topics = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $topics[] = $row;
        }
        return $topics;
    }

    // Validate topic input
    public function validateTopic($title, $description) {
        $errors = [];
        
        // Validate title
        if (empty($title)) {
            $errors['title'] = 'Title is required';
        } elseif (strlen($title) < 5) {
            $errors['title'] = 'Title must be at least 5 characters long';
        } elseif (strlen($title) > 255) {
            $errors['title'] = 'Title must be less than 255 characters';
        }

        // Validate description
        if (empty($description)) {
            $errors['description'] = 'Description is required';
        } elseif (strlen($description) < 10) {
            $errors['description'] = 'Description must be at least 10 characters long';
        }

        return $errors;
    }

    // Get total topics count
    public function getTotalTopicsCount() {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        return $data['count'];
    }

    // Check if user owns topic
    public function isTopicOwner($topicId, $username) {
        $sql = "SELECT user FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $topicId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $topic = mysqli_fetch_assoc($result);
        return $topic && $topic['user'] === $username;
    }
}
?>