<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/login_signup.css">
</head>
<body>
    <section class="container grey-text">
        <h4 class="center">Sign Up</h4>
        <form class="white" action="index.php?url=signup/processSignup" method="POST">
            <label>Your Email:</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($data['data']['email'] ?? ''); ?>">
            <div class="red-text"><?php echo $data['errors']['email'] ?? ''; ?></div>

            <label>Password:</label>
            <input type="password" name="pass">
            <div class="red-text"><?php echo $data['errors']['pass'] ?? ''; ?></div>

            <label>Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($data['data']['username'] ?? ''); ?>">
            <div class="red-text"><?php echo $data['errors']['username'] ?? ''; ?></div>

            <div class="center">
                <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
            </div>
        </form>
    </section>
</body>
</html>