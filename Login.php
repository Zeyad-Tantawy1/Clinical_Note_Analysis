<?php
// Include database connection singleton
include('config/connect_DB.php');
session_start();

class UserLogin
{
    private $conn;
    public $email;
    public $password;
    public $errors = ['email' => '', 'password' => ''];

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function validateInputs($postData)
    {
        $this->email = mysqli_real_escape_string($this->conn, $postData['email']);
        $this->password = mysqli_real_escape_string($this->conn, $postData['password']);

        if (empty($this->email)) {
            $this->errors['email'] = 'An Email is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email must be a valid email address.';
        }

        if (empty($this->password)) {
            $this->errors['password'] = 'Password is required.';
        }

        return !array_filter($this->errors);
    }

    public function login()
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $this->email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if ($this->password === $user['pass']) { // Use password_verify if passwords are hashed
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: index.php');
                exit;
            } else {
                $this->errors['password'] = 'Incorrect password.';
            }
        } else {
            $this->errors['email'] = 'Email not found.';
        }

        mysqli_stmt_close($stmt);
        return false;
    }
}

$userLogin = new UserLogin();

if (isset($_POST['submit'])) {
    if ($userLogin->validateInputs($_POST)) {
        $userLogin->login();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<link rel="stylesheet" href="LoginAndSignup.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="forum.js"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="email">Email</label>
            <input type="text" id="email" name="email" required>
            <div class="red-text"><?php echo $userLogin->errors['email']; ?></div>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="red-text"><?php echo $userLogin->errors['password']; ?></div>

            <button type="submit" name="submit" value="submit" class="btn">Login</button>
            <a class="waves-effect waves-teal btn-flat" href="SignUp.php">SignUp</a>
        </form>
    </div>
</body>
</html>
