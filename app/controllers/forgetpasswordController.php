<?php
class ForgetPasswordController extends Controller {
    public function __construct() {
    }

    public function index() {
        $this->view('forgetPassword');
    }
}
?>