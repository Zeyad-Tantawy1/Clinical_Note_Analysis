<?php
require_once(__DIR__ . '/../models/UserManager.php');

class UserManagementController extends Controller {
    private $userManager;

    public function __construct() {
        if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
            header('Location: index.php?url=home/index');
            exit();
        }
        $this->userManager = new UserManager();
    }

    public function index() {
        $users = $this->userManager->fetchAllUsers();
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        
        $data = [
            'users' => $users,
            'message' => $message
        ];
        
        $this->view('userManagement', $data);
    }

    public function deleteUser($id) {
        if ($this->userManager->deleteUser($id)) {
            header('Location: index.php?url=userManagement/index?message=User deleted successfully');
        } else {
            header('Location: index.php?url=userManagement/index?message=Error deleting user');
        }
        exit();
    }
}
?>