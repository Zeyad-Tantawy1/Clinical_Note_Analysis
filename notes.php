<?php
include('config/connect_DB.php');

class UserManager {
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchUsersOrderedByCreatedAt()
    {
        $sql = 'SELECT username, id, created_at, description FROM users ORDER BY created_at DESC';
        $result = mysqli_query($this->conn, $sql);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $users;
    }

    public function fetchAllUsers()
    {
        $sql = 'SELECT * FROM users';
        $result = mysqli_query($this->conn, $sql);
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $users;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

$userManager = new UserManager();
$userData = $userManager->fetchUsersOrderedByCreatedAt();
$allUsers = $userManager->fetchAllUsers();
$userManager->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinical Notes</title>
    <link rel="stylesheet" href="styles/Admin_Dash.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include('templates/header.php'); ?>
    <div class="notes-section">
        <h2>Clinical Notes</h2>
        <table class="notes-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>User ID</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($userData as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['description']); ?></td>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="Admin_Dash.js"></script>
</body>
</html>
