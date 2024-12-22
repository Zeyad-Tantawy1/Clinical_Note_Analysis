<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/userManagement.css">
</head>
<body>
<?php include('partials/header.php'); ?>

<div class="container">
    <?php if (isset($data['message']) && !empty($data['message'])): ?>
        <p class="success-text"><?php echo $data['message']; ?></p>
    <?php endif; ?>

    <?php if (!empty($data['users'])): ?>
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
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['pass']); ?></td>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo strtoupper(htmlspecialchars($user['role'])); ?></td>
                        <td>
                            <a href="index.php?url=userManagement/deleteUser/<?php echo $user['id']; ?>" 
                               class="btn brand z-depth-0" 
                               onclick="return confirm('Are you sure you want to delete this user?');">
                                Delete
                            </a>
                            <a href="index.php?url=userEdit/index/<?php echo $user['id']; ?>" 
                               class="btn brand z-depth-0">
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php?url=signup/index" class="btn brand z-depth-0">Add User</a>
    <?php else: ?>
        <h5 class="red-text">No users found.</h5>
    <?php endif; ?>
</div>

<?php include('partials/footer.php'); ?>
</body>
</html>