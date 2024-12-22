<?php
include('config/connect_DB.php');
class UserManager {
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function addUser($id, $username, $email, $password)
    {
        $stmt = $this->conn->prepare("INSERT INTO users (id, username, email, pass) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id, $username, $email, $password);
        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function fetchAllUsers()
    {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

$userManager = new UserManager();
$message = '';
$errors = ['email' => '', 'pass' => '', 'username' => '', 'id' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $id = $_POST['id'];

    if (empty($email)) {
        $errors['email'] = 'An email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email must be a valid email address.';
    }

    if (empty($password)) {
        $errors['pass'] = 'A password is required.';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $errors['pass'] = 'Password must contain an uppercase letter, lowercase letter, number, and special character.';
    }

    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    if (empty($id) || !is_numeric($id)) {
        $errors['id'] = 'ID is required and must be numeric.';
    }

    if (!array_filter($errors)) {
        if ($userManager->addUser($id, $username, $email, $password)) {
            $message = "User added successfully.";
            header("Location: userManagement.php");
            exit();
        } else {
            $message = "Error adding user.";
        }
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    if ($userManager->deleteUser($delete_id)) {
        $message = "User deleted successfully.";
        header("Location: userManagement.php");
        exit();
    } else {
        $message = "Error deleting user.";
    }
}

$users = $userManager->fetchAllUsers();
$userManager->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styles/userManagement.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    
</head>
<body>
<?php include('templates/header.php'); ?>

<div class="container">
    <?php if (!empty($message)): ?>
        <p class="success-text"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (!empty($users)): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['pass']); ?></td>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo strtoupper(htmlspecialchars($user['role'])); ?></td>
                        <td>
                            <a href="userManagement.php?delete_id=<?php echo $user['id']; ?>" class="btn brand z-depth-0" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn brand z-depth-0">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="SignUp.php" class="btn brand z-depth-0">Add User</a>
    <?php else: ?>
        <h5 class="red-text">No users found.</h5>
    <?php endif; ?>
</div>

<?php include('templates/footer.php'); ?>
</body>
</html>

