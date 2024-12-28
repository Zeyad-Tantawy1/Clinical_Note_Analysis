<?php
require_once('../app/models/User.php');

class EditProfileController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        if (!isset($_SESSION['username'])) {
            header('Location: index.php?url=login/index');
            exit();
        }

        $userData = $this->userModel->getUserByUsername($_SESSION['username']);
        if (!$userData) {
            header('Location: index.php?url=login/index');
            exit();
        }

        $data = [
            'userData' => $userData,
            'errors' => []
        ];

        $this->view('editprofile', $data);
    }

    public function update() {
        if (!isset($_SESSION['username'])) {
            header('Location: index.php?url=login/index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = $this->userModel->getUserByUsername($_SESSION['username']);
            $errors = [];
            
            // Validate username
            $username = trim($_POST['username']);
            if (empty($username)) {
                $errors['username'] = 'Username is required';
            } elseif (strlen($username) < 3) {
                $errors['username'] = 'Username must be at least 3 characters long';
            } elseif ($username !== $_SESSION['username'] && $this->userModel->isUsernameTaken($username)) {
                $errors['username'] = 'Username is already taken';
            }

            // Validate email
            $email = trim($_POST['email']);
            if (empty($email)) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            } elseif ($email !== $userData['email'] && $this->userModel->isEmailTaken($email)) {
                $errors['email'] = 'Email is already registered';
            }

            // Validate password if provided
            if (!empty($_POST['new_password'])) {
                $password = $_POST['new_password'];
                if (strlen($password) < 8) {
                    $errors['password'] = 'Password must be at least 8 characters long';
                } elseif (!preg_match('/[A-Z]/', $password)) {
                    $errors['password'] = 'Password must contain at least one uppercase letter';
                } elseif (!preg_match('/[a-z]/', $password)) {
                    $errors['password'] = 'Password must contain at least one lowercase letter';
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $errors['password'] = 'Password must contain at least one number';
                } elseif (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
                    $errors['password'] = 'Password must contain at least one special character';
                } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
                    $errors['password'] = 'Passwords do not match';
                }
            }

            if (empty($errors)) {
                $updateData = [
                    'username' => $username,
                    'email' => $email
                ];

                if ($this->userModel->updateProfile($userData['id'], $updateData)) {
                    if (!empty($_POST['new_password'])) {
                        $this->userModel->changePassword($userData['id'], $_POST['new_password']);
                    }
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['success_message'] = 'Profile updated successfully';
                } else {
                    $_SESSION['error_message'] = 'Failed to update profile';
                }
            } else {
                $_SESSION['errors'] = $errors;
            }

            header('Location: index.php?url=editprofile');
            exit();
        }
    }

    private function validatePassword($password) {
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password) &&
               preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password);
    }

    private function sanitizeInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
?>