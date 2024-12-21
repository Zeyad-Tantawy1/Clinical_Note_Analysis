<?php
require_once 'config/connect_DB.php'; // Include the Database singleton

class Dat
{
    private $conn;

    public function __construct() {
        $db = Database::getInstance(); // Get the Database instance
        $this->conn = $db->getConnection(); // Get the connection
    }

    public function __destruct()
    {
        mysqli_close($this->conn);
    }

    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            die("Query failed: " . mysqli_error($this->conn));
        }
        return $result;
    }

    public function fetchAll($result)
    {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function closeResult($result)
    {
        mysqli_free_result($result);
    }
}

class AdminDashboard
{
    private $db;

    public function __construct(Dat $db)
    {
        $this->db = $db;
    }

    public function getUserData()
    {
        $sql = 'SELECT username, id, created_at, description FROM users ORDER BY created_at DESC';
        $result = $this->db->query($sql);
        $userData = $this->db->fetchAll($result);
        $this->db->closeResult($result);
        return $userData;
    }

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $result = $this->db->query($sql);
        $users = $this->db->fetchAll($result);
        $this->db->closeResult($result);
        return $users;
    }

    public function getNoteCount()
    {
        $sql = "SELECT COUNT(*) AS row_count FROM note";
        $result = $this->db->query($sql);
        $row = $this->db->fetchAll($result)[0];
        $this->db->closeResult($result);
        return $row['row_count'];
    }
}

// Corrected: Use the Dat class to handle database queries
$db = new Dat(); // Instantiate the Dat class (not Database directly)
$adminDashboard = new AdminDashboard($db);

$userData = $adminDashboard->getUserData();
$users = $adminDashboard->getAllUsers();
$noteCount = $adminDashboard->getNoteCount();

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinical Note Analysis - Admin Dashboard</title>
    <link rel="stylesheet" href="styles/Admin_Dash.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include('templates/header.php');?>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h2>ClinicalNotes</h2>
            </div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="notes.php"><i class="fas fa-notes-medical"></i> Notes</a></li>
                <li><a href="userManagement.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <header class="main-header">
                <h1>Dashboard</h1>
                <div class="profile-menu">
                    
                    
                </div>
            </header>

            <!-- Stats Section -->
            <section class="dashboard-stats">
                <div class="stat-card">
                    <h3>Total Clinical Notes</h3>
                    <p>12,345</p>
                </div>
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <p><?php echo isset($userData) ? count($userData) : 0; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Notes Analyzed Today</h3>
                    <p>145</p>
                </div>
                <div class="stat-card">
                    <h3>Pending Notes</h3>
                    <p>34</p>
                </div>
            </section>
            <script src="Admin_Dash.js?v=<?php echo time(); ?>"></script>

            <!-- Charts Section -->
            <section class="charts-section">
    <div class="chart">
        <h3>Notes Analyzed Per Day</h3>
        <label for="weekPicker">Select a Week:</label>
        <input type="week" id="weekPicker">
        <canvas id="notesChart"></canvas>
    </div>

    <div class="chart">
        <h3>Note Status Distribution</h3>
        <canvas id="statusChart"></canvas>
    </div>
</section>

           <!-- Notes Table -->
           <section class="notes-section">
                <h3>Recent Clinical Notes</h3>
                <table class="notes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>User ID</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $counter=0;?>
                        <?php foreach ($userData as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['description']); ?></td>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        </tr>
                    <?php $counter++; ?>
                    <?php if ($counter == 5) break; ?>
                <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <a class="waves-effect waves-teal btn-flat" href="notes.php">See More</a>
        </div>
    </div>

    
</body>
</html>


<!-- <ul class="collection">
    <li class="collection-item avatar">
      <img src="images/yuna.jpg" alt="" class="circle">
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle">folder</i>
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle green">insert_chart</i>
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle red">play_arrow</i>
      <span class="title">Title</span>
      <p>First Line <br>
         Second Line
      </p>
      <a href="#!" class="secondary-content"><i class="material-icons">grade</i></a>
    </li>
  </ul> -->






 <!-- <tr>
                            <td>1</td>
                            <td>Patient A - Diagnosis</td>
                            <td>Completed</td>
                            <td>Dr. Smith</td>
                            <td>2024-11-25</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Patient B - Follow-Up</td>
                            <td>Pending</td>
                            <td>Dr. Jones</td>
                            <td></td>
                        </tr> -->
