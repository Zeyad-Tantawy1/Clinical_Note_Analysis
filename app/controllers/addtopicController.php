<?php
require_once('../app/models/Topic.php');

class AddTopicController extends Controller {
    private $topicModel;

    public function __construct() {
        $this->topicModel = new Topic();
    }

    public function index() {
        if (!isset($_SESSION['username'])) {
            header('Location: index.php?url=login/index');
            exit();
        }
        $this->view('addTopic');
    }

    public function create() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['username'])) {
            $title = $_POST['title'] ?? '';
            $description = $_POST['content'] ?? '';
            $username = $_SESSION['username'];

            if ($title && $description) {
                if ($this->topicModel->createTopic($title, $description, $username)) {
                    header('Location: index.php?url=forum/index');
                    exit();
                }
            }
            header('Location: index.php?url=addTopic/index');
            exit();
        }
    }
}
?>