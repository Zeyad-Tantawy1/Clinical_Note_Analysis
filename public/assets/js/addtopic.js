document.getElementById('newTopicForm').addEventListener('submit', function(event) {
    const title = document.getElementById('title').value.trim();
    const content = document.getElementById('content').value.trim();
    const errorMessage = document.getElementById('error-message');
    errorMessage.style.display = 'none';
    if (!title || !content) {
        event.preventDefault();
        errorMessage.style.display = 'block';
    }
});
