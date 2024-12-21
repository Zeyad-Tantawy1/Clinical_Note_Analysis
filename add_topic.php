

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Topic</title>
    <link rel="stylesheet" href="styles/add_topic.css">
</head>
<body>
<?php include('templates/header.php');?>
    <div class="new-topic-container">
        <h1>Create a New Topic</h1>
        <form id="newTopicForm" class="new-topic-form" action="submit_topic.php" method="POST">
            <label for="title">Topic Title</label>
            <input type="text" id="title" name="title" required placeholder="Enter the topic title">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="10" required placeholder="Describe the topic in detail"></textarea>
            <input type="hidden" id="user" name="user" value="<?php echo htmlspecialchars($username); ?>">
            <button type="submit">Add Topic</button>
        </form>
        <div id="error-message" class="error-message" style="display: none;">Please fill in all required fields.</div>
    </div>
    <script src="add_topic.js"></script>
</body>
</html>
