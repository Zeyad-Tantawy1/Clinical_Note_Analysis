<?php
require_once 'config/connect_DB.php'; // Include the Database singleton

class Analytics {
    private $conn;

    public function __construct() {
        $db = Database::getInstance(); // Get the Database instance
        $this->conn = $db->getConnection(); // Get the connection
    }

    public function getNotesPerDay($startDate, $endDate) {
        $sql = "SELECT DATE(date) as day, COUNT(*) as count FROM topics WHERE date BETWEEN ? AND ? GROUP BY DATE(date)";  // Changed from 'note' to 'topics'
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
<style>
    /* General Styles */
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f5f7;
    color: #333;
    line-height: 1.6;
}

/* Header */
header {
    background-color: #2563eb;
    color: white;
    padding: 1rem 2rem;
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
}

/* Form Styles */
form {
    background: #fff;
    padding: 1.5rem 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    margin: 2rem auto;
}

form label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

form input[type="week"] {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 1rem;
    font-size: 1rem;
    color: #555;
}

form button {
    display: inline-block;
    background-color: #2563eb;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #1d4ed8;
}

/* Section Heading */
h2 {
    text-align: center;
    margin-top: 2rem;
    color: #1f2937;
    font-size: 1.8rem;
    font-weight: 700;
}

/* Chart Container */
canvas {
    display: block;
    max-width: 600px;
    margin: 2rem auto;
    background: #fff;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        padding: 1rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    canvas {
        max-width: 100%;
    }
}

</style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include('partials/header.php'); ?>

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
<?php include('partials/footer.php'); ?>

</body>
</html>

