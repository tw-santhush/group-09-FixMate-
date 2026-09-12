<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<section class="dashboard-section">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div>
                <h1>Welcome, <?php echo $user['name']; ?></h1>
                <p>Your account information</p>
            </div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="dashboard-grid">
            <div class="profile-card">
                <h2>Your Profile</h2>

                <div class="profile-item">
                    <label>Name:</label>
                    <span><?php echo $user['name']; ?></span>
                </div>
                <div class="profile-item">
                    <label>Email:</label>
                    <span><?php echo $user['email']; ?></span>
                </div>
                <div class="profile-item">
                    <label>Phone:</label>
                    <span><?php echo $user['phone']; ?></span>
                </div>
                <div class="profile-item">
                    <label>Member Since:</label>
                    <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                </div>

                <a href="edit_user_profile.php" class="edit-btn">Edit Profile</a>
            </div>

            <div class="stats-card">
                <h2>Account Type</h2>

                <div class="stat-box">
                    <div class="stat-label">Regular User</div>
                </div>

                <div class="stat-box">
                    <p>You can search for service professionals, view ratings, and contact them directly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
</html>