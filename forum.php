<?php
include('config/connect_DB.php');
session_start(); // Start the session

class ForumManager {
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchAllNotes()
    {
        $sql = "SELECT title, description, date, user FROM note";
        $result = $this->conn->query($sql);
        $notes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $notes[] = $row;
            }
        }
        return $notes;
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

$forumManager = new ForumManager();
$notes = $forumManager->fetchAllNotes();
$forumManager->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="styles/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="forum.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <h1>Clinical Note Analysis</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                    <li><a href="contact.php"><i class="fa-solid fa-phone"></i> Contact</a></li>
                    <li><a href="#about">About Us</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="#profile"><i class="fa-solid fa-user"></i><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                        <li><a href="logout.php">Logout</a></li>
                        <?php if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin'): ?>
                            <li><a href="Admin_Dash.php">Dashboard</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fa-solid fa-user"></i> Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>
        <div class="subforum">
            <div class="subform-banner">
                <img src="images/clinical-banner.jpg" alt="Banner">
                <h1>Welcome To Our Medical Discussions Forum</h1>
                <p>Do you have a question? Ask specialists</p>
            </div>
            <div class="search-container-wrapper">
                <h2>Recent Topics</h2>
                <div class="search-container">
                    <input type="text" class="search-bar" placeholder="Search">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="new-topic-icon">
                    <a href="add_topic.php"><i class="fa-solid fa-square-plus"></i></a>
                </div>
            </div>

            <?php if (!empty($notes)): ?>
                <?php foreach ($notes as $note): ?>
                    <div class="subforum-row">
                        <div class="subforum-icon subforum-column center">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="subforum-title subforum-column center">
                            <h2><a href="#"><?php echo htmlspecialchars($note['title']); ?></a></h2>
                        </div>
                        <div class="subforum-replies subforum-column center">
                            <p>0 Replies</p>
                        </div>
                        <div class="subforum-info subforum-column center">
                            <p>Posted By: <a href="#"><?php echo htmlspecialchars($note['user']); ?></a><br>Posted On: <?php echo htmlspecialchars($note['date']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-topics">No Topics Available</div>
            <?php endif; ?>
        </div>
        <?php include('templates/footer.php'); ?>
    </div>
</body>
</html>
