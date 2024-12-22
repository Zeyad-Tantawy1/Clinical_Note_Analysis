<?php
require_once(__DIR__ . '/../models/Topic.php');
require_once(__DIR__ . '/../models/Comment.php');

class TopicController extends Controller
{
    private $topicModel;
    private $commentModel;

    public function __construct() {
        session_start();
        $this->topicModel = new Topic();
        $this->commentModel = new Comment();
    }

    public function index($id = null) {
        // Validate and clean the ID parameter
        if (!$id || (strpos($id, 'index&id=') !== false)) {
            $id = strpos($id, 'index&id=') !== false ? 
                  substr($id, strpos($id, '=') + 1) : $id;
        }

        if (!$id) {
            header("Location: index.php?url=forum/index?error=invalidtopic");
            exit();
        }

        // Get topic data using Topic model
        $topic = $this->topicModel->getTopicById($id);
        
        // If topic doesn't exist, redirect to forum
        if (!$topic) {
            header("Location: index.php?url=forum/index?error=topicnotfound");
            exit();
        }

        // Get comments using Comment model
        $comments = $this->commentModel->getCommentsByTopicId($id);

        // Pass data to view
        $data = [
            'topic' => $topic,
            'comments' => $comments ?? [],
            'error' => $_GET['error'] ?? null,
            'success' => $_GET['success'] ?? null
        ];

        $this->view('topic', $data);
    }

    public function addComment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?url=forum/index");
            exit();
        }
    
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?url=login/index");
            exit();
        }
    
        $topicId = filter_input(INPUT_POST, 'topic_id', FILTER_VALIDATE_INT);
        $content = trim($_POST['comment_content'] ?? '');
        $user = $_SESSION['username'];
    
        // Validate inputs
        if (!$topicId || empty($content)) {
            header("Location: index.php?url=topic/index/{$topicId}?error=invalidinput");
            exit();
        }
    
        // Verify topic exists
        if (!$this->topicModel->getTopicById($topicId)) {
            header("Location: index.php?url=forum/index?error=topicnotfound");
            exit();
        }
    
        // Add comment
        if ($this->commentModel->addComment($topicId, $content, $user)) {
            header("Location: index.php?url=topic/index/{$topicId}?success=commentadded");
        } else {
            header("Location: index.php?url=topic/index/{$topicId}?error=commentfailed");
        }
        exit();
    }

    public function deleteComment($commentId) {
        if (!isset($_SESSION['username'])) {
            header("Location: index.php?url=login/index");
            exit();
        }

        $comment = $this->commentModel->getCommentById($commentId);
        if (!$comment) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Check if user is comment owner or admin
        if ($comment['user'] !== $_SESSION['username'] && 
            (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin')) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }

        if ($this->commentModel->deleteComment($commentId)) {
            // Update topic's reply count
            $this->topicModel->updateReplyCount($comment['topic_id']);
            header("Location: index.php?url=topic/index/{$comment['topic_id']}?success=commentdeleted");
        } else {
            header("Location: index.php?url=topic/index/{$comment['topic_id']}?error=deletefailed");
        }
        exit();
    }
}
?>