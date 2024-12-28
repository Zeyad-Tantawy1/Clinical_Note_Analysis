<link rel="stylesheet" href="http://localhost/project/public/assets/css/header.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <h1>Clinical Notes Analysis</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php?url=home/index"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="index.php?url=forum/index"><i class="fa-solid fa-comments"></i> Forum</a></li>
                <li><a href="#contact"><i class="fa-solid fa-phone"></i> Contact</a></li>
                <li><a href="#about"><i class="fa-solid fa-circle-info"></i> About</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="user-menu">
                        <a href="index.php?url=editprofile/index">
                            <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                    </li>
                <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin'): ?>
                    <li>
                        <a href="index.php?url=admin/index">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard
                        </a>
                    </li>
                <?php endif; ?>
                    <li><a href="index.php?url=login/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="index.php?url=login/index"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>