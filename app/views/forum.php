<?php
include('config/connect_DB.php');
class ForumManager {
    private $conn;
    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }
    public function fetchAllTopics()  // Changed from fetchAllNotes
    {
        $sql = "SELECT id, title, description, date, user, replies FROM topics";
        $result = $this->conn->query($sql);
        $topics = [];  // Changed from notes
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $topics[] = $row;  // Changed from notes
            }
        }
        return $topics;  // Changed from notes
    }
    public function closeConnection()
    {
        $this->conn->close();
    }
}
$forumManager = new ForumManager();
$topics = $forumManager->fetchAllTopics();  // Changed from notes
$forumManager->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="forum.js"></script>
</head>
<body>
    <div class="container">
    <?php include('partials/header.php'); ?>
        <div class="subforum">
            <div class="subform-banner">
                <img src="http://localhost/project/public/assets/img/clinical-banner.jpg" alt="Banner">
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
                <a href="index.php?url=addTopic/index" class="add-topic-btn"><i class="fa-solid fa-square-plus"></i></a>
                </div>
            </div>

            <?php if (!empty($topics)): ?>  <!-- Changed from notes -->
                <?php foreach ($topics as $topic): ?>  <!-- Changed from notes as note -->
                    <div class="subforum-row">
                        <div class="subforum-icon subforum-column center">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="subforum-title subforum-column center">
                            <h2><a href="index.php?url=topic/index&id=<?php echo $topic['id']; ?>"><?php echo htmlspecialchars($topic['title']); ?></a></h2>
                        </div>
                        <div class="subforum-replies subforum-column center">
                            <p><?php echo htmlspecialchars($topic['replies']); ?> Replies</p>
                        </div>
                        <div class="subforum-info subforum-column center">
                            <p>Posted By: <?php echo htmlspecialchars($topic['user']); ?><br>Posted On: <?php echo htmlspecialchars($topic['date']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-topics">No Topics Available</div>
            <?php endif; ?>
        </div>
        <?php include('partials/footer.php'); ?>
    </div>
</body>
</html>