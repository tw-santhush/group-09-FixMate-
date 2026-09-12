<?php
/** @var mysqli $conn */

include '../includes/config.php';

// Live stats from the database
$tech_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM technicians"))['c'];
$dist_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM districts"))['c'];

include '../includes/header.php';
?>

<!-- Header Section -->
<section class="hero">
    <div class="hero-content">
        <h1>The <span class="highlight">Smart</span> Way to Find a<br><span class="service-box">Service Pro</span></h1>
        <p>Stop hunting for torn posters on walls. Browse verified local plumbers, electricians, <br> carpenters, mechanics & painters — with phone numbers & ratings.</p>
        <div class="hero-buttons">
            <div class="hero-buttons">
                <a href="find.php" class="btn-primary">Find a Service Pro</a>
                <a href="login_technician.php" class="btn-secondary">Technician Login</a>
            </div>
        </div>
    </div>
</section>

<!-- Browse Services Section -->
<section class="browse-services">
    <div class="find-container">
        <h2>Browse Services by Category</h2>
        <p>Find professionals for your needs</p>
        
        <div class="services-grid">
            <a href="find.php?service=1" class="service-card">
                <div class="service-icon">🔧</div>
                <div class="service-name">Plumber</div>
            </a>
            
            <a href="find.php?service=2" class="service-card">
                <div class="service-icon">⚡</div>
                <div class="service-name">Electrician</div>
            </a>
            
            <a href="find.php?service=3" class="service-card">
                <div class="service-icon">🪚</div>
                <div class="service-name">Carpenter</div>
            </a>
            
            <a href="find.php?service=4" class="service-card">
                <div class="service-icon">🔩</div>
                <div class="service-name">Mechanic</div>
            </a>
            
            <a href="find.php?service=5" class="service-card">
                <div class="service-icon">🎨</div>
                <div class="service-name">Painter</div>
            </a>
            
            <a href="find.php?service=6" class="service-card">
                <div class="service-icon">🔥</div>
                <div class="service-name">Welder</div>
            </a>
            
            <a href="find.php?service=7" class="service-card">
                <div class="service-icon">🧱</div>
                <div class="service-name">Mason</div>
            </a>
            
            <a href="find.php?service=8" class="service-card">
                <div class="service-icon">🔐</div>
                <div class="service-name">Locksmith</div>
            </a>
            
            <a href="find.php?service=9" class="service-card">
                <div class="service-icon">❄️</div>
                <div class="service-name">Appliance Repair</div>
            </a>
            
            <a href="find.php?service=10" class="service-card">
                <div class="service-icon">🌡️</div>
                <div class="service-name">AC Technician</div>
            </a>
            
            <a href="find.php?service=11" class="service-card">
                <div class="service-icon">📱</div>
                <div class="service-name">Phone Repair</div>
            </a>
            
            <a href="find.php?service=12" class="service-card">
                <div class="service-icon">💻</div>
                <div class="service-name">Computer Tech</div>
            </a>
            
            <a href="find.php?service=13" class="service-card">
                <div class="service-icon">🚰</div>
                <div class="service-name">Plumbing & Gas</div>
            </a>
            
            <a href="find.php?service=14" class="service-card">
                <div class="service-icon">☀️</div>
                <div class="service-name">Electrical & Solar</div>
            </a>
            
            <a href="find.php?service=15" class="service-card">
                <div class="service-icon">🛠️</div>
                <div class="service-name">General Repairs</div>
            </a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works">
    <div class="how-container">
        
        <h2>Three Steps to Get Help</h2>
        
        <div class="steps-grid">
            <!-- Step 1 -->
            <div class="step-card">
                <span class="step-number">1</span>
                <div class="step-icon">📍</div>
                <h3>Choose Your District</h3>
                <p>Select your district from the dropdown and see available handymen near you.</p>
            </div>
            
            <!-- Step 2 -->
            <div class="step-card">
                <span class="step-number">2</span>
                <div class="step-icon">🔍</div>
                <h3>Pick a Service</h3>
                <p>Filter by trade — plumbing, electrical, carpentry, painting, mechanics & more.</p>
            </div>
            
            <!-- Step 3 -->
            <div class="step-card">
                <span class="step-number">3</span>
                <div class="step-icon">📞</div>
                <h3>Call & Fix It</h3>
                <p>Tap the number, talk directly to the FixMate, and get your job done.</p>
            </div>
        </div>
    </div>
</section> 

<!-- About Section -->
<section class="about" id="about">
    <div class="about-container">
        <div class="about-content">
            <div class="about-text">
                
                <h2>Bringing Sri Lanka's <br>Handymen Online</h2>
                
                <p>In Sri Lanka, most local tradesmen still rely on word-of-mouth and paper posters pasted on walls and telephone poles. FixMate is a simple digital directory that changes that — for free.</p>
                
                <p>No booking fees, no commissions, no accounts needed. Just a searchable list of real handymen with their phone numbers and community ratings.</p>
                
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $tech_count; ?></div>
                        <div class="stat-label">Registered Pros</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $dist_count; ?></div>
                        <div class="stat-label">Districts Covered</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Free to Use</div>
                    </div>
                </div>
            </div>
            
            <div class="about-image">
                <img src="../images/handyman.jpg" alt="Handyman at work">
            </div>
        </div>
    </div>
</section>


<!-- Service Pro Bottom Register Section -->
<section class="service-pro-cta">
    <div class="cta-container">
        <div class="cta-label">For Professionals</div>
        <h2>Are You a <span class="highlight">Service Pro ?</span></h2>
        <p>Get listed on FixMate for free and start receiving calls from customers in your area. <br>No fees. No commissions.</p>
        <a href="register_technician.php" class="cta-button">Register for Free</a>
    </div>
</section>

<!-- The white color section with a paragraph -->
<section class="blank-space-section">
  <div class="blank-section-content">
    <p> FixMate. The Smart Way to Find a Service Pro.</p>
  </div>
</section>

</body>
</html>