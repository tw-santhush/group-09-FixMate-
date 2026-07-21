<?php
/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login_admin.php');
    exit();
}

// Handle delete user (POST only)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['delete_user']);
    // Delete reviews associated with this user first
    $delete_reviews = "DELETE FROM ratings WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_reviews);
    // Then delete user
    $delete_query = "DELETE FROM users WHERE id = '$user_id'";
    mysqli_query($conn, $delete_query);
    header('Location: admin_panel.php');
    exit();
}

// Handle delete technician (POST only)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_tech'])) {
    $tech_id = mysqli_real_escape_string($conn, $_POST['delete_tech']);
    // Delete reviews associated with this technician first
    $delete_reviews = "DELETE FROM ratings WHERE technician_id = '$tech_id'";
    mysqli_query($conn, $delete_reviews);
    // Then delete technician
    $delete_query = "DELETE FROM technicians WHERE id = '$tech_id'";
    mysqli_query($conn, $delete_query);
    header('Location: admin_panel.php');
    exit();
}

// Get statistics
$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$techs_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM technicians"))['count'];

// Get all users
$users_query = "SELECT id, name, email, created_at FROM users ORDER BY created_at DESC";
$users_result = mysqli_query($conn, $users_query);

// Get all technicians
$techs_query = "SELECT t.id, t.name, t.email, t.phone, s.service_name, d.district_name, t.created_at
                FROM technicians t
                JOIN services s ON t.service_id = s.id
                JOIN districts d ON t.district_id = d.id
                ORDER BY t.created_at DESC";
$techs_result = mysqli_query($conn, $techs_query);
?>

<!-- Admin Panel Section -->
<section class="admin-section">
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Panel</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid admin-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $users_count; ?></div>
                <div class="stat-text">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $techs_count; ?></div>
                <div class="stat-text">Total Technicians</div>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="admin-table-section">
            <h2>All Users</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_user" value="<?php echo $user['id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Technicians Table -->
        <div class="admin-table-section">
            <h2>All Technicians</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Service</th>
                        <th>District</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($tech = mysqli_fetch_assoc($techs_result)): ?>
                        <tr>
                            <td><?php echo $tech['id']; ?></td>
                            <td><?php echo htmlspecialchars($tech['name']); ?></td>
                            <td><?php echo htmlspecialchars($tech['email']); ?></td>
                            <td><?php echo htmlspecialchars($tech['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($tech['district_name']); ?></td>
                            <td><?php echo htmlspecialchars($tech['phone']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_tech" value="<?php echo $tech['id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Delete this technician?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

</body>
</html>