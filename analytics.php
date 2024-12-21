<?php
require_once 'config/connect_DB.php'; // Include the Database singleton

class Analytics {
    private $conn;

    public function __construct() {
        $db = Database::getInstance(); // Get the Database instance
        $this->conn = $db->getConnection(); // Get the connection
    }

    public function getNotesPerDay($startDate, $endDate) {
        $sql = "SELECT DATE(date) as day, COUNT(*) as count FROM note WHERE date BETWEEN ? AND ? GROUP BY DATE(date)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        $notesPerDay = [];

        while ($row = $result->fetch_assoc()) {
            $notesPerDay[] = $row;
        }

        $stmt->close();
        return $notesPerDay;
    }
}

// Create an instance of the Analytics class
$analytics = new Analytics();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $week = $_POST['week'] ?? null;

    if ($week) {
        $year = substr($week, 0, 4);
        $weekNumber = substr($week, -2);

        $startDate = date('Y-m-d', strtotime($year . "W" . $weekNumber . "1"));
        $endDate = date('Y-m-d', strtotime($year . "W" . $weekNumber . "7"));

        $notesPerDay = $analytics->getNotesPerDay($startDate, $endDate);
    } else {
        $notesPerDay = [];
    }
} else {
    $notesPerDay = [];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include('templates/header.php'); ?>

<form method="POST" action="">
    <label for="week">Choose a Week:</label>
    <input type="week" id="week" name="week" required>
    <button type="submit">Fetch Data</button>
</form>

<h2>Notes Analyzed Per Day</h2>
<canvas id="notesChart" width="400" height="200"></canvas>

<script>
    const labels = <?= json_encode(array_column($notesPerDay, 'day')); ?>;
    const data = <?= json_encode(array_column($notesPerDay, 'count')); ?>;

    if (labels.length && data.length) {
        const ctx = document.getElementById('notesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Notes Analyzed',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } else {
        document.getElementById('notesChart').remove();
        
    }
</script>

</body>
</html>

