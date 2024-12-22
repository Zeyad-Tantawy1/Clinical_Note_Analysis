<?php
require_once('../app/config/connect_DB.php');

class User {
    private $conn;
    private $table = 'users';

    // User properties
    public $id;
    public $username;
    public $email;
    public $pass;
    public $role;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Get user by ID
    public function getUserById($id) {
        $sql = "SELECT id, username, email, role FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Get user by username
    public function getUserByUsername($username) {
        $sql = "SELECT id, username, email, role FROM {$this->table} WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    // Verify login with improved security
    public function verifyLogin($email, $password) {
        $sql = "SELECT id, username, email, pass, role FROM {$this->table} WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($user = mysqli_fetch_assoc($result)) {
            // In a production environment, use password_verify() with hashed passwords
            if ($password === $user['pass']) { 
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
            }
        }
        return false;
    }

    // Add this method to your User class
public function validateLogin($postData) {
    $errors = ['email' => '', 'password' => ''];
    
    // Validate email
    if (empty($postData['email'])) {
        $errors['email'] = 'An Email is required.';
    } elseif (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email must be a valid email address.';
    }

    // Validate password
    if (empty($postData['password'])) {
        $errors['password'] = 'Password is required.';
    }

    $this->errors = $errors;  // Store errors in the class property
    return !array_filter($errors);
}

// Add this property at the top of your class with other properties
public $errors = ['email' => '', 'password' => ''];

    // Validate input with improved validation
    public function validateInput($postData) {
        $errors = ['email' => '', 'pass' => '', 'username' => '', 'role' => '', 'general' => ''];
        $data = [
            'email' => '',
            'pass' => '',
            'username' => '',
            'role' => 'USER'
        ];

        // Validate email
        if (empty($postData['email'])) {
            $errors['email'] = 'An email is required';
        } else {
            $data['email'] = filter_var($postData['email'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email must be a valid email address';
            }
        }

        // Validate password
        if (empty($postData['pass'])) {
            $errors['pass'] = 'Password is required';
        } else {
            $data['pass'] = $postData['pass'];
            if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/", $data['pass'])) {
                $errors['pass'] = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character';
            }
        }

        // Validate username
        if (empty($postData['username'])) {
            $errors['username'] = 'Username is required';
        } else {
            $data['username'] = filter_var($postData['username'], FILTER_SANITIZE_STRING);
            if (strlen($data['username']) < 3 || strlen($data['username']) > 20) {
                $errors['username'] = 'Username must be between 3 and 20 characters';
            }
        }

        return ['data' => $data, 'errors' => $errors];
    }

    // Check unique fields with improved error handling
    public function checkUniqueFields($username, $email) {
        $errors = ['username' => '', 'email' => ''];
        
        $sql = "SELECT * FROM {$this->table} WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        
        if (!mysqli_stmt_execute($stmt)) {
            $errors['general'] = 'Database error occurred';
            return $errors;
        }
        
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['username'] === $username) {
                $errors['username'] = 'Username already exists. Please choose a different one.';
            }
            if ($row['email'] === $email) {
                $errors['email'] = 'Email already exists. Please choose a different one.';
            }
        }

        mysqli_stmt_close($stmt);
        return $errors;
    }

    // Create user with improved security
    public function createUser($data) {
        // In a production environment, hash the password before storing
        // $hashedPassword = password_hash($data['pass'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO {$this->table} (username, email, pass, role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", 
            $data['username'], 
            $data['email'], 
            $data['pass'], // Use $hashedPassword in production
            $data['role']
        );
        
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Update user profile
    public function updateProfile($userId, $data) {
        $sql = "UPDATE {$this->table} SET username = ?, email = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $data['username'], $data['email'], $userId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Change password
    public function changePassword($userId, $newPassword) {
        // In production, hash the new password
        // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE {$this->table} SET pass = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $newPassword, $userId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Delete user account
    public function deleteAccount($userId) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }

    // Check if user is admin
    public function isAdmin($userId) {
        $sql = "SELECT role FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        return $user && strtoupper($user['role']) === 'ADMIN';
    }

    // Get all users (admin function)
    public function getAllUsers() {
        $sql = "SELECT id, username, email, role FROM {$this->table}";
        $result = mysqli_query($this->conn, $sql);
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
        return $users;
    }

    public function loadUserById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) {
            $this->id = $user['id'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->role = $user['role'];
            $this->pass = $user['pass'];
        }
        mysqli_stmt_close($stmt);
        return $user;
    }
    
    public function validateEditInputs($postData) {
        $errors = ['email' => '', 'pass' => '', 'username' => '', 'role' => ''];
    
        if (empty($postData['email'])) {
            $errors['email'] = 'An email is required.';
        } elseif (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email must be a valid email address.';
        }
    
        if (empty($postData['pass'])) {
            $errors['pass'] = 'A password is required.';
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $postData['pass'])) {
            $errors['pass'] = 'Password must contain an uppercase letter, lowercase letter, number, special character and must be 8 length.';
        }
    
        if (empty($postData['username'])) {
            $errors['username'] = 'Username is required.';
        }
    
        if (empty($postData['role'])) {
            $errors['role'] = 'Role is required.';
        } elseif (!in_array(strtolower($postData['role']), ['admin', 'user'])) {
            $errors['role'] = 'Role must be Admin or User.';
        }
    
        return $errors;
    }
    
    public function updateUserDetails($id, $data) {
        $sql = "UPDATE {$this->table} SET username = ?, email = ?, pass = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $data['username'], 
            $data['email'], 
            $data['pass'],
            $data['role'],
            $id
        );
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $success;
    }
}
?>