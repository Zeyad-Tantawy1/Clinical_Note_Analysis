<?php
$isLoggedIn = $data['isLoggedIn'] ?? false;
$username = $data['username'] ?? null;
$role = $data['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic</title>
    <link rel="stylesheet" href="http://localhost/project/public/assets/css/home.css">
    <script src="http://localhost/project/public/assets/js/home.js"></script>
    </head>
<body>
    <?php include('partials/header.php'); ?>
        <div class="content">
        <div class="image-container">
            <img src="http://localhost/project/public/assets/img/back1.png" alt="background">
        </div>
            <h1 class="clinic-name">ACE LAB FOR CLINICAL NOTE ANALYSIS</h1>
            <p class="tagline">Unlock valuable insights from your clinical notes with the power of AI</p>
            <div class="buttons">
                <a href="#" class="btn btn-primary">Try For Free</a>
            </div>
        </div>
    <!-- About Section -->
    <section id="about" class="about">
    <div class="about-container">
        <h2 class="about-title">About Us</h2>
        
        <div class="about-card">
            <div class="about-content">
                <div class="about-text">
                    <h3>Who We Are</h3>
                    <p>Our Clinical Note Analysis System is designed to revolutionize how healthcare professionals manage and interpret clinical notes. By leveraging cutting-edge technologies, we streamline workflows, improve accuracy, and enhance patient care.</p>
                </div>
                <div class="about-image">
                    <img src="http://localhost/project/public/assets/img/analyze.jpg" alt="Who We Are">
                </div>
            </div>
        </div>

        <div class="about-card reverse">
            <div class="about-content">
                <div class="about-text">
                    <h3>Our Goal</h3>
                    <p>We aim to empower healthcare providers with tools that simplify complex data, improve decision-making, and foster a future where technology bridges the gap between patient data and actionable insights.</p>
                </div>
                <div class="about-image">
                    <img src="http://localhost/project/public/assets/img/target.jpg" alt="Our Goal">
                </div>
            </div>
        </div>

        <div class="about-card">
            <div class="about-content">
                <div class="about-text">
                    <h3>Why Choose Us</h3>
                    <p>What sets us apart is our commitment to innovation, security, and user-centric design. With a focus on accuracy and efficiency, we ensure healthcare professionals can focus on what truly matters: delivering exceptional patient care.</p>
                </div>
                <div class="about-image">
                    <img src="http://localhost/project/public/assets/img/why.jpg" alt="Why Choose Us">
                </div>
            </div>
        </div>
    </div>
</section>

    
    <!-- Features Section -->
    <section class="inclusive-section">
        <div class="container">
            <div class="inclusive-content">
                <div class="inclusive-image">
                    <img src="http://localhost/project/public/assets/img/review.jpg" alt="Wheelchair inclusivity">
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

    <!-- Cards Section -->
    <section id="features" class="card-section">
        <h2 class="section-title">Explore Our Features</h2>
        <div class="card-grid">
            <!-- Analyze Card -->
            <div class="card">
                <img src="http://localhost/project/public/assets/img/analytic.jpg" alt="Analyze Clinical Notes">
                <h3>Analyze</h3>
                <p>Effortlessly analyze clinical notes using our intelligent system for deeper insights.</p>
                <button class="btn" onclick="window.location.href='<?php echo $isLoggedIn ? 'test.php' : 'index.php?url=login/index'; ?>'">
                    Learn More
                </button>
            </div>

            <!-- Ask a Professional Card -->
            <div class="card">
                <img src="http://localhost/project/public/assets/img/ask-us.jpg" alt="Ask a Professional">
                <h3>Ask a Professional</h3>
                <p>Connect with experienced professionals to get answers to your queries quickly.</p>
                <button class="btn" onclick="window.location.href='<?php echo $isLoggedIn ? 'index.php?url=forum/index' : 'index.php?url=login/index'; ?>'">
                    Go to Forum
                </button>
            </div>

            <!-- Access History Card -->
            <div class="card">
                <img src="http://localhost/project/public/assets/img/medicalHistory.jpg" alt="Access History">
                <h3>Access History</h3>
                <p>Securely access past notes and data, ensuring seamless patient care continuity.</p>
                <button class="btn" onclick="window.location.href='<?php echo $isLoggedIn ? 'lesa.php' : 'index.php?url=login/index'; ?>'">
                    Learn More
                </button>
            </div>
        </div>
    </section>
    <!-- Contact Section -->
    <section  id="contact" class="contact-section">
        <h2>Contact Us</h2>
        <div class="contact-container">
            <div class="contact-info">
                <h3>Get in Touch</h3>
                <p>We would love to hear from you. Whether you have questions or need support, reach out to us.</p>
                <p><strong>Email:</strong> support@clinicalnotes.com</p>
                <p><strong>Phone:</strong> +1 (234) 567-8901</p>
                <p><strong>Address:</strong> 123 Healthcare Blvd, Tech City</p>
            </div>
            <div class="contact-form">
                <h3>Send a Message</h3>
                <form method="POST" action="submit_contact.php">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                    
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                    
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <?php include('partials/footer.php'); ?>
</body>
</html>
