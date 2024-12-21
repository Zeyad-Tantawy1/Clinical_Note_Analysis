<?php
// Include database connection singleton
include('config/connect_DB.php');

class User
{
    private $conn;
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $errors = ['email' => '', 'pass' => '', 'username' => '', 'id' => '', 'role' => ''];

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function loadUserById($id)
    {
        $this->id = mysqli_real_escape_string($this->conn, $id);
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $this->id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        if ($user) {
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->role = $user['role'];
        }
        mysqli_stmt_close($stmt);
    }

    public function validateInputs($postData)
    {
        $this->username = mysqli_real_escape_string($this->conn, $postData['username']);
        $this->email = mysqli_real_escape_string($this->conn, $postData['email']);
        $this->password = mysqli_real_escape_string($this->conn, $postData['pass']);
        $this->role = mysqli_real_escape_string($this->conn, strtoupper($postData['role']));

        if (empty($this->email)) {
            $this->errors['email'] = 'An email is required.';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email must be a valid email address.';
        }

        if (empty($this->password)) {
            $this->errors['pass'] = 'A password is required.';
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $this->password)) {
            $this->errors['pass'] = 'Password must contain an uppercase letter, lowercase letter, number, special character and must be 8 length.';
        }

        if (empty($this->username)) {
            $this->errors['username'] = 'Username is required.';
        }

        if (empty($postData['role'])) {
            $this->errors['role'] = 'Role is required.';
        } elseif (!in_array(strtolower($postData['role']), ['admin', 'user'])) {
            $this->errors['role'] = 'Role must be Admin or User.';
        }

        return !array_filter($this->errors);
    }

    public function checkUniqueFields()
    {
        $sql = "SELECT * FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $this->username, $this->email, $this->id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['username'] === $this->username) {
                $this->errors['username'] = 'Username already exists. Please choose a different one.';
            }
            if ($row['email'] === $this->email) {
                $this->errors['email'] = 'Email already exists. Please choose a different one.';
            }
        }

        mysqli_stmt_close($stmt);
        return !array_filter($this->errors);
    }

    public function updateUser()
    {
        $sql = "UPDATE users SET username = ?, email = ?, pass = ?, role = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssi", $this->username, $this->email, $this->password, $this->role, $this->id);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return $success;
        } else {
            throw new Exception('Statement preparation error: ' . mysqli_error($this->conn));
        }
    }
}

// Instantiate User class
$user = new User();

if (isset($_GET['id'])) {
    $user->loadUserById($_GET['id']);
}

if (isset($_POST['action'])) {
    if ($user->validateInputs($_POST) && $user->checkUniqueFields()) {
        try {
            if ($user->updateUser()) {
                header("Location: userManagement.php");
                exit();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

<div class="container center grey-text">
    <div class="row">
        <form action="edit.php?id=<?php echo htmlspecialchars($user->id); ?>" method="POST" class="col s12">
            <div class="row">
                <div class="input-field col s6">
                    <input value="<?php echo htmlspecialchars($user->username); ?>" name="username" id="username" type="text" class="validate">
                    <label class="active" for="username">User Name</label>
                    <div class="red-text"><?php echo $user->errors['username']; ?></div>
                </div>
                <div class="input-field col s6">
                    <input disabled value="<?php echo htmlspecialchars($user->id); ?>" id="id" type="text" class="validate">
                    <label class="active" for="id">ID</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input value="<?php echo htmlspecialchars($user->email); ?>" name="email" id="email" type="text" class="validate">
                    <label class="active" for="email">Email</label>
                    <div class="red-text"><?php echo $user->errors['email']; ?></div>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6">
                    <input value="<?php echo htmlspecialchars($user->password); ?>" name="pass" id="password" type="password" class="validate">
                    <label class="active" for="password">Password</label>
                    <div class="red-text"><?php echo $user->errors['pass']; ?></div>
                </div>
                <div class="input-field col s6">
                    <input value="<?php echo htmlspecialchars($user->role); ?>" name="role" id="role" type="text" class="validate">
                    <label class="active" for="role">Role</label>
                    <div class="red-text"><?php echo $user->errors['role']; ?></div>
                </div>
            </div>

            <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                <i class="material-icons right">send</i>
            </button>
        </form>
    </div>
</div>

<?php include('templates/footer.php'); ?>
</body>
</html>
