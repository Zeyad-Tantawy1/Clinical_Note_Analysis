<?php
require_once(__DIR__ . '/../models/Topic.php');

class DiscussionsController extends Controller {
    private $topicModel;

    public function __construct() {
        session_start();
        // Check if user is logged in and is admin
        if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
            header('Location: index.php?url=home/index');
            exit();
        }
        $this->topicModel = new Topic();
    }

    public function index() {
        $data = [
            'topics' => $this->topicModel->getAllTopics(),
            'totalTopics' => $this->topicModel->getTotalTopicsCount()
        ];
        
        $this->view('discussions', $data);
    }

    public function deleteTopic($id) {
        if ($this->topicModel->deleteTopic($id)) {
            header('Location: index.php?url=discussions/index?success=Topic deleted successfully');
        } else {
            header('Location: index.php?url=discussions/index?error=Failed to delete topic');
        }
        exit();
    }

    public function search() {
        if (isset($_GET['term'])) {
            $searchTerm = trim($_GET['term']);
            $data = [
                'topics' => $this->topicModel->searchTopics($searchTerm),
                'searchTerm' => $searchTerm
            ];
            $this->view('discussions', $data);
        } else {
            header('Location: index.php?url=discussions/index');
            exit();
        }
    }
}
?>