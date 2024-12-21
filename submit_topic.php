<?php
session_start();
include('config/connect_DB.php');  // Include your existing Database Singleton

// SubmitTopic class for submitting topics
class SubmitTopic {
    private $conn;

    // Constructor receives the database connection
    public function __construct() {
        // Get the singleton instance of the database connection
        $this->conn = Database::getInstance()->getConnection();
    }

    // Submit the topic to the database
    public function submitTopic($title, $description, $user, $date) {
        $stmt = $this->conn->prepare("INSERT INTO note (title, description, user, date) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $title, $description, $user, $date);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        } else {
            throw new Exception("Error preparing statement: " . $this->conn->error);
        }
    }

    // Close the database connection (not strictly necessary, but a good practice)
    public function closeConnection() {
        $this->conn->close();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the form data
        $title = $_POST['title'];
        $description = $_POST['content'];
        $user = $_SESSION['username']; // Ensure this session variable is set
        $date = date('Y-m-d H:i:s');

        // Create the SubmitTopic object and submit the topic
        $submitTopic = new SubmitTopic();

        // Submit the topic to the database
        if ($submitTopic->submitTopic($title, $description, $user, $date)) {
            header("Location: forum.php");
            exit();
        } else {
            echo "Error submitting topic.";
        }

        // Close the connection (optional)
        $submitTopic->closeConnection();
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}
?>
