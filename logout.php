<?php
include('config/connect_DB.php');

session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
