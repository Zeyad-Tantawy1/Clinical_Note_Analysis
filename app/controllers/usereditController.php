<?php
require_once(__DIR__ . '/../models/User.php');

class UserEditController extends Controller {
    private $userModel;

    public function __construct() {
        session_start();
        // Check if user is logged in and is admin
        if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'admin') {
            header('Location: index.php?url=home/index');
            exit();
        }
        $this->userModel = new User();
    }

    public function index($id = null) {
        if (!$id) {
            header('Location: index.php?url=userManagement/index');
            exit();
        }

        // Load user data
        $user = $this->userModel->loadUserById($id);
        if (!$user) {
            header('Location: index.php?url=userManagement/index');
            exit();
        }

        $data = [
            'user' => $user,
            'errors' => []
        ];
        
        $this->view('userEdit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postData = [
                'id' => $_POST['id'],
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'pass' => $_POST['pass'],
                'role' => $_POST['role']
            ];

            // Load current user data
            $this->userModel->loadUserById($postData['id']);

            // Validate inputs
            $errors = $this->userModel->validateEditInputs($postData);
            
            if (empty(array_filter($errors))) {
                if ($this->userModel->updateUserDetails($postData['id'], $postData)) {
                    header('Location: index.php?url=userManagement/index?message=User updated successfully');
                    exit();
                } else {
                    $errors['general'] = 'Error updating user';
                }
            }

            // If there are errors, go back to edit page with errors
            $data = [
                'user' => $postData,
                'errors' => $errors
            ];
            $this->view('userEdit', $data);
        } else {
            header('Location: index.php?url=userManagement/index');
            exit();
        }
    }
}
?>