<?php 
include('config/connect_DB.php');
session_start(); // Start the session

// Enable error reporting during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>ACE Lab</title>
    <style>
        /* Root Variables for Theme */
        :root {
    --primary-color: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1e40af;
    --secondary-color: #f0f9ff;
    --text-light: #ffffff;
    --text-dark: #1f2937;
    --background-color: #f9fafb;
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --radius-md: 0.75rem;
    --spacing-md: 1rem;
    --spacing-lg: 1.5rem;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Improved Global Styles */
body {
    margin: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: var(--background-color);
    color: var(--text-dark);
}

/* Enhanced Navbar */
.navbar {
    background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
    padding: 1rem;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    transition: var(--transition);
}

.navbar.scrolled {
    background: rgba(37, 99, 235, 0.95);
    backdrop-filter: blur(10px);
    padding: 0.75rem 1rem;
}

.navbar .container {
    max-width: 1400px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 2rem;
}

/* Logo Styling */
.navbar .logo {
    font-size: 1.8rem;
    font-weight: 800;
    background: linear-gradient(135deg, #ffffff, #e2e8f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    position: relative;
    margin: 0;
}

.logo::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--text-light);
    transition: var(--transition);
}

.logo:hover::after {
    width: 100%;
}

/* Navigation Links */
.navbar .nav-links {
    display: flex;
    gap: 1.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
}

.navbar .nav-links li {
    position: relative;
}

.navbar .nav-links a {
    color: var(--text-light);
    font-size: 0.95rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    letter-spacing: 0.3px;
}

.navbar .nav-links a:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
}

/* Special Styling for Active/Login Links */
.navbar .nav-links a[href="login.php"],
.navbar .nav-links a[href="logout.php"] {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1.25rem;
}

.navbar .nav-links a[href="login.php"]:hover,
.navbar .nav-links a[href="logout.php"]:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Username Display */
.navbar .nav-links a[href="#profile"] {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 2rem;
    padding: 0.5rem 1.25rem;
}

/* Modern Hamburger Menu */
.menu-toggle {
    display: none;
    background: transparent;
    border: none;
    width: 40px;
    height: 40px;
    padding: 0.5rem;
    cursor: pointer;
    position: relative;
}

.menu-toggle span {
    display: block;
    width: 24px;
    height: 2px;
    background: var(--text-light);
    position: absolute;
    left: 8px;
    transition: var(--transition);
}

.menu-toggle span:nth-child(1) { top: 12px; }
.menu-toggle span:nth-child(2) { top: 19px; }
.menu-toggle span:nth-child(3) { top: 26px; }

.menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg);
    top: 19px;
}

.menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg);
    top: 19px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .navbar .container {
        padding: 0 1rem;
    }
}

@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    .navbar .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: var(--primary-dark);
        padding: 1rem 0;
        flex-direction: column;
        gap: 0.5rem;
        box-shadow: var(--shadow-lg);
        transform: translateY(-10px);
        opacity: 0;
        transition: var(--transition);
    }

    .navbar .nav-links.show {
        display: flex;
        transform: translateY(0);
        opacity: 1;
    }

    .navbar .nav-links li {
        width: 100%;
    }

    .navbar .nav-links a {
        padding: 0.75rem 2rem;
        width: 100%;
        display: flex;
        justify-content: center;
        border-radius: 0;
    }

    .navbar .nav-links a:hover {
        background: rgba(255, 255, 255, 0.05);
        transform: none;
    }
}

/* Smooth Scroll Behavior */
html {
    scroll-behavior: smooth;
    scroll-padding-top: 80px;
}
    </style>


   
    <!-- Your CSS file here -->
</head>
<body>
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
                    <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin'): ?>
                        <li><a href="Admin_Dash.php">Dashboard</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
            <button class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <script>
        // Enhanced Mobile Navigation Toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');
        
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('show');
            menuToggle.classList.toggle('active');
        });

        // Scroll Effect for Navbar
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
