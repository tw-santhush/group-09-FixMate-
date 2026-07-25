<?php
/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT id, name, email, phone, created_at FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header('Location: login.php');
    exit();
}
?>

<!-- User Profile Section -->
<section class="dashboard-section">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
            <p>Your account information</p>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <div class="dashboard-grid">
            <!-- Profile Card -->
            <div class="profile-card">
                <h2>Your Profile</h2>
                
                <div class="profile-item">
                    <label>Name:</label>
                    <span><?php echo htmlspecialchars($user['name']); ?></span>
                </div>
                
                <div class="profile-item">
                    <label>Email:</label>
                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                
                <div class="profile-item">
                    <label>Phone:</label>
                    <span><?php echo htmlspecialchars($user['phone']); ?></span>
                </div>
                
                <div class="profile-item">
                    <label>Member Since:</label>
                    <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                </div>
                
                <a href="edit_user_profile.php" class="edit-btn">Edit Profile</a>
            </div>
            
            <!-- Account Info Card -->
            <div class="stats-card">
                <h2>Account Type</h2>
                
                <div class="stat-box">
                    <div class="stat-label">Regular User</div>
                    <div class="stat-value" style="font-size: 24px; margin-top: 8px;">👤</div>
                </div>
                
                <div class="stat-box" style="background-color: #fef3c7; margin-top: 16px;">
                    <div class="stat-label">You can:</div>
                    <div style="font-size: 13px; color: #92400e; margin-top: 8px; line-height: 1.6;">
                        ✓ Search for service professionals<br>
                        ✓ View ratings and contact info<br>
                        ✓ Call or message via WhatsApp<br>
                        ✓ Manage your account
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>