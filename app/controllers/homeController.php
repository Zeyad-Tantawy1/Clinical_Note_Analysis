<?php
require_once(__DIR__ . '/../models/UserDataFetcher.php');

class HomeController extends Controller
{
    private $userDataFetcher;

    public function __construct() {
        $this->userDataFetcher = new UserDataFetcher();
    }

    public function index()
    {
        $data = [
            'userIds' => $this->userDataFetcher->fetchUserIds(),
            'isLoggedIn' => isset($_SESSION['username']),
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? null
        ];
        
        $this->view('home', $data);
    }
}
?>