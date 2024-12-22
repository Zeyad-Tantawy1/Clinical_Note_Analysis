<?php
require_once 'config/connect_DB.php'; // Include the Database singleton

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $db = Database::getInstance()->getConnection();

        // Validate the token
        $stmt = $db->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if (strtotime($row['expires_at']) > time()) {
                // Update the password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param('ss', $hashedPassword, $row['email']);
                $stmt->execute();

                // Delete the token
                $stmt = $db->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param('s', $token);
                $stmt->execute();

                $success = "Your password has been reset successfully.";
            } else {
                $error = "The token has expired.";
            }
        } else {
            $error = "Invalid token.";
        }
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    $error = "Invalid access.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
<h2>Reset Password</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error; ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= $success; ?></p>
<?php else: ?>
    <form method="POST" action="">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token); ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="submit">Reset Password</button>
    </form>
<?php endif; ?>
</body>
</html>
