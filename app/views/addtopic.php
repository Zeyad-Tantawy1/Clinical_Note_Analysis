<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Topic</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/addtopic.css">
</head>
<body>
    <div class="new-topic-container">
        <h1>Create a New Topic</h1>
        <form id="newTopicForm" class="new-topic-form" action="index.php?url=addTopic/create" method="POST">
            <label for="title">Topic Title</label>
            <input type="text" id="title" name="title" required placeholder="Enter the topic title">
            
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="10" required placeholder="Describe the topic in detail"></textarea>
            
            <input type="hidden" id="user" name="user" value="<?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : ''; ?>">
            
            <button type="submit" name="submit">Add Topic</button>
        </form>
        <div id="error-message" class="error-message" style="display: none;">Please fill in all required fields.</div>
    </div>
    <script src="http://localhost/project/public/assets/js/addtopic.js"></script>
</body>
</html>