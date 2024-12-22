<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussions Management</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/discussions.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include('partials/header.php'); ?>
    
    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <div class="search-section">
            <form action="index.php?url=discussions/search" method="GET" class="search-form">
                <input type="text" name="term" placeholder="Search discussions..." 
                       value="<?php echo isset($data['searchTerm']) ? htmlspecialchars($data['searchTerm']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="discussions-section">
            <div class="section-header">
                <h2>Forum Discussions (<?php echo $data['totalTopics']; ?>)</h2>
            </div>

            <table class="discussions-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Date</th>
                        <th>Replies</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['topics'])): ?>
                        <?php foreach ($data['topics'] as $topic): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($topic['id']); ?></td>
                                <td>
                                    <a href="index.php?url=topic/index/<?php echo $topic['id']; ?>">
                                        <?php echo htmlspecialchars($topic['title']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($topic['user']); ?></td>
                                <td><?php echo date('F j, Y', strtotime($topic['date'])); ?></td>
                                <td><?php echo htmlspecialchars($topic['replies']); ?></td>
                                <td class="actions">
                                    <a href="index.php?url=topic/index/<?php echo $topic['id']; ?>" 
                                       class="view-btn" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?url=discussions/deleteTopic/<?php echo $topic['id']; ?>" 
                                       class="delete-btn" 
                                       onclick="return confirm('Are you sure you want to delete this topic?')"
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="no-results">No discussions found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('partials/footer.php'); ?>
</body>
</html>