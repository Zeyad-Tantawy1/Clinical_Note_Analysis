<?php 
include('config/connect_DB.php');

session_start(); // Start the session

// Enable error reporting during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<header>
    <style>
        /* Navigation Bar */
.navbar {
    background-color: #053c6b;
    color: #fff;
    padding: 10px 20px;
    position: sticky;
    top: 0;
    z-index: 1000;
}
.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.navbar .logo {
    font-size: 24px;
    font-weight: bold;
}
.navbar .nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
}
.navbar .nav-links a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
}
.navbar .nav-links a:hover {
    text-decoration: underline;
}
    </style>
<body class="grey lighten-4">
<nav class="navbar">
    <div class="container">
      <h1 class="logo">ACE Lab</h1>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="#about">About Us</a></li>
        <li><a href="#features">Features</a></li> 
        <li><a href="#contact">Contact</a></li>
        <?php if (isset($_SESSION['username'])): ?>
                <li><a href="#profile"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li><a href="logout.php">Logout</a></li>
                <?php if (isset($_SESSION['role']) && strtolower( $_SESSION['role']) === 'admin'): ?>
                    <li><a href="Admin_Dash.php">Dashboard</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
    </ul>
    
    </div>
  </nav>