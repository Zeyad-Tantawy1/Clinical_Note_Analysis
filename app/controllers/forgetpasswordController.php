<?php
class ForgetPasswordController extends Controller {
    public function __construct() {
        session_start();
    }

    public function index() {
        $this->view('forgetPassword');
    }
}
?>