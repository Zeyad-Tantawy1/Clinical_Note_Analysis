<!-- app/views/editprofile.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Clinical Notes Analysis</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/header.css">
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/editprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<?php include('partials/header.php'); ?>

    <section class="profile-section">
        <div class="profile-container">
            <div class="profile-header">
                <h2>Edit Profile</h2>
                <p>Update your account information</p>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php 
                        echo $_SESSION['success_message'];
                        unset($_SESSION['success_message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error">
                    <?php 
                        echo $_SESSION['error_message'];
                        unset($_SESSION['error_message']);
                    ?>
                </div>
            <?php endif; ?>

            <form id="editProfileForm" action="index.php?url=editprofile/update" method="POST">
            <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo htmlspecialchars($data['userData']['username']); ?>" required>
                    <?php if (isset($_SESSION['errors']['username'])): ?>
                        <span class="error-text"><?php echo $_SESSION['errors']['username']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($data['userData']['email']); ?>" required>
                    <?php if (isset($_SESSION['errors']['email'])): ?>
                        <span class="error-text"><?php echo $_SESSION['errors']['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password (leave blank to keep current)</label>
                    <input type="password" id="new_password" name="new_password">
                    <?php if (isset($_SESSION['errors']['password'])): ?>
                        <span class="error-text"><?php echo $_SESSION['errors']['password']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password">
                </div>

                <div class="profile-actions">
                    <button type="submit" class="btn-save">Save Changes</button>
                    <a href="index.php?url=home/index" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </section>

    <?php
    // Clear any remaining error messages
    if (isset($_SESSION['errors'])) {
        unset($_SESSION['errors']);
    }
    ?>
        <?php include('partials/footer.php'); ?>
        <script src="http://localhost/project/public/assets/js/editprofile.js"></script>
</body>
</html>