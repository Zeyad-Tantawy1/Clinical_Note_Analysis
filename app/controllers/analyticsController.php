<?php
class AnalyticsController extends Controller {
    public function __construct() {
        // Check if user is logged in and is admin
        if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
            header('Location: index.php?url=home/index');
            exit();
        }
    }

    public function index() {
        $this->view('analytics');  // This will load analytics.php from views folder
    }
}
?>