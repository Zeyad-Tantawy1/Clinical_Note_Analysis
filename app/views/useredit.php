<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
</head>
<body>
    <?php include('partials/header.php'); ?>

    <div class="container center grey-text">
        <div class="row">
            <form action="index.php?url=userEdit/update" method="POST" class="col s12">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['user']['id']); ?>">
                
                <div class="row">
                    <div class="input-field col s6">
                        <input value="<?php echo htmlspecialchars($data['user']['username']); ?>" 
                               name="username" id="username" type="text" class="validate">
                        <label class="active" for="username">User Name</label>
                        <div class="red-text"><?php echo isset($data['errors']['username']) ? $data['errors']['username'] : ''; ?></div>
                    </div>
                    <div class="input-field col s6">
                        <input disabled value="<?php echo htmlspecialchars($data['user']['id']); ?>" 
                               id="id" type="text" class="validate">
                        <label class="active" for="id">ID</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input value="<?php echo htmlspecialchars($data['user']['email']); ?>" 
                               name="email" id="email" type="text" class="validate">
                        <label class="active" for="email">Email</label>
                        <div class="red-text"><?php echo isset($data['errors']['email']) ? $data['errors']['email'] : ''; ?></div>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s6">
                        <input value="<?php echo htmlspecialchars($data['user']['pass']); ?>" 
                               name="pass" id="password" type="password" class="validate">
                        <label class="active" for="password">Password</label>
                        <div class="red-text"><?php echo isset($data['errors']['pass']) ? $data['errors']['pass'] : ''; ?></div>
                    </div>
                    <div class="input-field col s6">
                        <input value="<?php echo htmlspecialchars($data['user']['role']); ?>" 
                               name="role" id="role" type="text" class="validate">
                        <label class="active" for="role">Role</label>
                        <div class="red-text"><?php echo isset($data['errors']['role']) ? $data['errors']['role'] : ''; ?></div>
                    </div>
                </div>

                <button class="btn waves-effect waves-light" type="submit" name="action">Update
                    <i class="material-icons right">send</i>
                </button>
            </form>
        </div>
    </div>

    <?php include('partials/footer.php'); ?>
</body>
</html>