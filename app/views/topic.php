<?php
// Extract data safely from the $data array
$topic = $data['topic'] ?? null;
$comments = $data['comments'] ?? [];
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;

// Check if topic exists
if (!$topic) {
    header("Location: index.php?url=forum/index?error=topicnotfound");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($topic['title']); ?></title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/topic.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="topic-page">
    <?php include('partials/header.php'); ?>
    
    <div class="container">
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="topic-container">
            <div class="breadcrumb">
                <a href="index.php?url=forum/index"><i class="fas fa-arrow-left"></i> Back to Forums</a>
            </div>
            
            <div class="topic-header">
                <h1><?php echo htmlspecialchars($topic['title']); ?></h1>
                <div class="topic-meta">
                    <div class="author-info">
                        <div class="author-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="author-details">
                            <span class="author-name"><?php echo htmlspecialchars($topic['user']); ?></span>
                            <span class="post-date">
                                <i class="fas fa-clock"></i> 
                                <?php echo date('F j, Y', strtotime($topic['date'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="topic-content">
                <p><?php echo nl2br(htmlspecialchars($topic['description'])); ?></p>
            </div>

            <div class="comments-section">
                <div class="comments-header">
                    <h2>Reply (<?php echo count($comments); ?>)</h2>
                </div>
                
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="comment-form">
                        <div class="current-user">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </div>
                        <form method="POST" action="index.php?url=topic/addComment">
                            <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                            <textarea name="comment_content" placeholder="Share your thoughts..." required></textarea>
                            <button type="submit">
                                <i class="fas fa-paper-plane"></i> Post Comment
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="login-prompt">
                        <i class="fas fa-lock"></i>
                        <p>Please <a href="index.php?url=login/index">sign in</a> to join the discussion</p>
                    </div>
                <?php endif; ?>

                <div class="comments-list">
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment">
                                <div class="comment-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="author-details">
                                        <span class="author-name"><?php echo htmlspecialchars($comment['user']); ?></span>
                                        <span class="comment-date">
                                            <?php echo date('F j, Y g:i a', strtotime($comment['date'])); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-comments">
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('partials/footer.php'); ?>
</body>
</html>