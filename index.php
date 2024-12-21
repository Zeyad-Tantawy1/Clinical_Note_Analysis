<?php
// Include database connection singleton
include('config/connect_DB.php');

class UserDataFetcher {
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function fetchUserIds()
    {
        $sql = 'SELECT id FROM users';
        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_free_result($result);
            return $data;
        }

        return [];
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}

$fetcher = new UserDataFetcher();
$notes = $fetcher->fetchUserIds();
$fetcher->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dental Clinic</title>
  <link rel="stylesheet" href="styles/style.css">
  <script src="home.js"></script>
</head>
<body>
<?php include('templates/header.php');?>

  <header class="hero-section">
    <div class="content">
      <h1 class="clinic-name">ACE LAB FOR CLINICAL NOTE ANALYSIS</h1>
      <p class="tagline">Offering the best dental packages year-round</p>
      <h2 class="headline">High-quality dental care, made more accessible</h2>
      <div class="buttons">
        <a href="#" class="btn btn-primary">Visit Our Clinic</a>
        <a href="#" class="btn btn-secondary">Make an Appointment</a>
      </div>
    </div>
    <div class="image-container">
      <img src="images/ok.jpg" alt="reviewing clinical notes" />
    </div>
  </header>


  <section class="about-section">
    <!-- Who We Are -->
    <div class="about-row">
      <div class="about-text">
        <h2>Who We Are</h2>
        <p>
          Our Clinical Note Analysis System is designed to revolutionize how healthcare professionals manage and interpret clinical notes. By leveraging cutting-edge technologies, we streamline workflows, improve accuracy, and enhance patient care.
        </p>
      </div>
      <div class="about-image">
        <img src="images/analyze.jpg  " alt="Who We Are">
      </div>
    </div>
  
    <!-- Our Goal -->
    <div class="about-row reverse">
      <div class="about-text">
        <h2>Our Goal</h2>
        <p>
          We aim to empower healthcare providers with tools that simplify complex data, improve decision-making, and foster a future where technology bridges the gap between patient data and actionable insights.
        </p>
      </div>
      <div class="about-image">
        <img src="images/target.jpg " alt="Our Goal">
      </div>
    </div>
  
    <!-- Why Choose Us -->
    <div class="about-row">
      <div class="about-text">
        <h2>Why Choose Us</h2>
        <p>
          What sets us apart is our commitment to innovation, security, and user-centric design. With a focus on accuracy and efficiency, we ensure healthcare professionals can focus on what truly matters: delivering exceptional patient care.
        </p>
      </div>
      <div class="about-image">
        <img src="images/why.jpg" alt="Why Choose Us">
      </div>
    </div>
  </section>
  
              <section class="inclusive-section">
                <div class="container">
                  <div class="inclusive-content">
                    <div class="inclusive-image">
                      <img src="images/review.jpg" alt="Wheelchair inclusivity">
                    </div>
                    <div class="inclusive-text">
                      <h2>Our Key Features</h2>
                      <ul>
                        <li>Intelligent parsing of clinical notes.</li>
                        <li>Natural language processing to extract critical insights.</li>
                        <li>Secure and scalable data management.</li>
                        <li>Customizable for different medical specialties.</li>
                    </ul>
                    </div>
                  </div>
                </div>
              </section>
  
              <section id="features" class="card-section">
    <h2 class="section-title">Explore Our Features</h2>
    <div class="card-grid">
        <!-- Analyze Card -->
        <div class="card">
            <img src="images/analytic.jpg" alt="Analyze Clinical Notes">
            <h3>Analyze</h3>
            <p>Effortlessly analyze clinical notes using our intelligent system for deeper insights.</p>
            <button class="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'A7A.php' : 'login.php'; ?>'">Learn More</button>
        </div>
        <!-- Ask a Professional Card -->
        <div class="card">
            <img src="images/ask-us.jpg" alt="Ask a Professional">
            <h3>Ask a Professional</h3>
            <p>Connect with experienced professionals to get answers to your queries quickly.</p>
            <button class="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'forum.php' : 'login.php'; ?>'">Learn More</button>
        </div>
        <!-- Access History Card -->
        <div class="card">
            <img src="images/medicalHistory.jpg" alt="Access History">
            <h3>Access History</h3>
            <p>Securely access past notes and data, ensuring seamless patient care continuity.</p>
            <button class="btn" onclick="window.location.href='<?php echo isset($_SESSION['username']) ? 'lesa.php' : 'login.php'; ?>'">Learn More</button>
        </div>
        <?php include('templates/footer.php'); ?>
    </div>
</section>
