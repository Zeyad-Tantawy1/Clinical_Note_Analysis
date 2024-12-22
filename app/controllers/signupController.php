<?php
class SignupController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        $data = ['errors' => ['email' => '', 'pass' => '', 'username' => '', 'role' => '', 'general' => '']];
        $this->view('signup', $data);
    }

    public function processSignup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            $result = $this->userModel->validateInput($_POST);
            $data = $result['data'];
            $errors = $result['errors'];

            // Check unique fields
            $uniqueErrors = $this->userModel->checkUniqueFields($data['username'], $data['email']);
            $errors = array_merge($errors, $uniqueErrors);

            // If no errors, create user
            if (!array_filter($errors)) {
                if ($this->userModel->createUser($data)) {
                    header('Location: index.php?url=login/index');
                    exit();
                } else {
                    $errors['general'] = 'Error creating user';
                }
            }

            // If there were errors, show the form again with errors
            $this->view('signup', ['errors' => $errors, 'data' => $data]);
        }
    }
}
?>