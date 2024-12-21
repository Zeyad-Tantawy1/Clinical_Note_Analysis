<?php
include('config/connect_DB.php');
session_start(); // Start the session

class UserHandler {
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function validateInput(&$errors)
    {
        $email = $pass = $username = $role = '';
        $data = ['email' => $email, 'pass' => $pass, 'username' => $username, 'role' => $role];

        // Validate email
        if (empty($_POST['email'])) {
            $errors['email'] = 'An email is required';
        } else {
            $data['email'] = $_POST['email'];
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email must be a valid email address';
            }
        }

        // Validate password
        if (empty($_POST['pass'])) {
            $errors['pass'] = 'Password is required';
        } else {
            $data['pass'] = $_POST['pass'];
            if (!preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/", $data['pass'])) {
                $errors['pass'] = 'Password does not meet the criteria.';
            }
        }

        // Validate username
        if (empty($_POST['username'])) {
            $errors['username'] = 'Username is required';
        } else {
            $data['username'] = $_POST['username'];
        }

        // Validate role for admin
        if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') {
            if (empty($_POST['role'])) {
                $errors['role'] = 'Role is required.';
            } elseif (!in_array(strtolower($_POST['role']), ['admin', 'user'])) {
                $errors['role'] = 'Role must be Admin or User.';
            } else {
                $data['role'] = strtoupper($_POST['role']);
            }
        } else {
            $data['role'] = 'USER';
        }

        return $data;
    }

    public function checkUniqueFields($username, $email, &$errors)
    {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
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
    }

    public function createUser($data, &$errors)
    {
        if (!array_filter($errors)) {
            $sql = "INSERT INTO users (username, email, pass, role) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $sql);
            $hashedPass = password_hash($data['pass'], PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssss", $data['username'], $data['email'], $hashedPass, $data['role']);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                return true;
            } else {
                $errors['general'] = 'Query error: ' . mysqli_error($this->conn);
                mysqli_stmt_close($stmt);
            }
        }
        return false;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

$handler = new UserHandler();
$errors = ['email' => '', 'pass' => '', 'username' => '', 'role' => '', 'general' => ''];

if (isset($_POST['submit'])) {
    $data = $handler->validateInput($errors);
    $handler->checkUniqueFields($data['username'], $data['email'], $errors);
    
    if ($handler->createUser($data, $errors)) {
        $redirect = (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') ? 'userManagement.php' : 'Login.php';
        header("Location: $redirect");
        exit;
    }
}

$handler->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <section class="container grey-text">
        <h4 class="center">Sign Up</h4>
        <form class="white" action="SignUp.php" method="POST">
            <label>Your Email:</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            <div class="red-text"><?php echo $errors['email']; ?></div>

            <label>Password:</label>
            <input type="password" name="pass" value="<?php echo htmlspecialchars($_POST['pass'] ?? ''); ?>">
            <div class="red-text"><?php echo $errors['pass']; ?></div>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            <div class="red-text"><?php echo $errors['username']; ?></div>

            <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin'): ?>
                <label>Your Role:</label>
                <input type="text" name="role" value="<?php echo htmlspecialchars($_POST['role'] ?? ''); ?>">
                <div class="red-text"><?php echo $errors['role']; ?></div>
            <?php endif; ?>

            <div class="center">
                <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
            </div>
        </form>
    </section>
    <?php include('templates/footer.php'); ?>
</body>
</html>
