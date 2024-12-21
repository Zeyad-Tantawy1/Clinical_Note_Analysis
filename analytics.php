<?php
require_once 'config/connect_DB.php'; // Include the Database singleton

class Analytics {
    private $conn;
    private $analytics;

    public function __construct() {
        $db = Database::getInstance(); // Get the Database instance
        $this->conn = $db->getConnection(); // Get the connection
    }

    public function fetchDailyAnalytics() {
        $sql = "SELECT DATE(created_at) AS date, COUNT(*) AS notes_count FROM clinical_notes GROUP BY DATE(created_at)";
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }
        $this->analytics = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $this->analytics[] = $row;
        }
        mysqli_free_result($result);
    }

    public function getAnalytics() {
        return $this->analytics;
    }
}

// Create an instance of the Analytics class
$db = Database::getInstance(); // Get the Database instance
$conn = $db->getConnection(); // Get the connection
$analytics = new Analytics($conn);

// Fetch daily analytics
$analytics->fetchDailyAnalytics();

// Get the analytics data
$analyticsData = $analytics->getAnalytics();
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
<?php include('templates/header.php');?>
    <h2>Notes Analyzed Per Day</h2>
    <canvas id="notesChart" width="400" height="200"></canvas>
    <script>
        const labels = <?= json_encode(array_column($analytics, 'date')); ?>;
        const data = <?= json_encode(array_column($analytics, 'notes_count')); ?>;
        
        const ctx = document.getElementById('notesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
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
    </script>
</body>
</html>
