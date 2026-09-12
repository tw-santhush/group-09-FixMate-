<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['tech_id'])) {
    header('Location: login_technician.php');
    exit();
}

$tech_id = $_SESSION['tech_id'];

$query = "SELECT t.*, s.service_name, d.district_name 
          FROM technicians t
          JOIN services s ON t.service_id = s.id
          JOIN districts d ON t.district_id = d.id
          WHERE t.id = '$tech_id'";
$result = mysqli_query($conn, $query);
$tech = mysqli_fetch_assoc($result);
?>

<section class="dashboard-section">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Welcome, <?php echo $tech['name']; ?></h1>
                <p>Here's your service professional profile</p>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="dashboard-grid">
            <div class="profile-card">
                <h2>Your Profile</h2>

                <div class="profile-item">
                    <label>Service Type:</label>
                    <span><?php echo $tech['service_name']; ?></span>
                </div>
                <div class="profile-item">
                    <label>District:</label>
                    <span><?php echo $tech['district_name']; ?></span>
                </div>
                <div class="profile-item">
                    <label>Phone:</label>
                    <span><?php echo $tech['phone']; ?></span>
                </div>
                <div class="profile-item">
                    <label>Email:</label>
                    <span><?php echo $tech['email']; ?></span>
                </div>
                <div class="profile-item">
                    <label>Experience:</label>
                    <span><?php echo $tech['experience']; ?> years</span>
                </div>
                <div class="profile-item">
                    <label>Bio:</label>
                    <span><?php echo $tech['bio']; ?></span>
                </div>

                <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
            </div>

            <div class="stats-card">
                <h2>Your Statistics</h2>

                <div class="stat-box">
                    <div class="stat-value"><?php echo number_format($tech['rating'], 1); ?></div>
                    <div class="stat-label">Rating (out of 5)</div>
                </div>

                <div class="stat-box">
                    <div class="stat-value"><?php echo $tech['jobs_completed']; ?></div>
                    <div class="stat-label">Jobs Completed</div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>