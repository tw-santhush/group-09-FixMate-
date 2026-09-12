<?php
include '../includes/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php');
    exit();
}

if (isset($_POST['delete_user'])) {
    $id = $_POST['delete_user'];
    mysqli_query($conn, "DELETE FROM ratings WHERE user_id = '$id'");
    mysqli_query($conn, "DELETE FROM users WHERE id = '$id'");
    header('Location: admin_panel.php');
    exit();
}

if (isset($_POST['delete_tech'])) {
    $id = $_POST['delete_tech'];
    mysqli_query($conn, "DELETE FROM ratings WHERE technician_id = '$id'");
    mysqli_query($conn, "DELETE FROM technicians WHERE id = '$id'");
    header('Location: admin_panel.php');
    exit();
}

$users_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$techs_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM technicians"))['c'];

$users = mysqli_query($conn, "SELECT id, name, email, created_at FROM users ORDER BY id DESC");

$techs = mysqli_query($conn, "SELECT t.id, t.name, t.email, t.phone, s.service_name, d.district_name
                              FROM technicians t
                              JOIN services s ON t.service_id = s.id
                              JOIN districts d ON t.district_id = d.id
                              ORDER BY t.id DESC");

include '../includes/header.php';
?>

<section class="admin-section">
    <div class="admin-container">
        <div class="admin-header">
            <h1>Admin Panel</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

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

        <div class="admin-table-section">
            <h2>All Users</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Joined</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo $u['name']; ?></td>
                            <td><?php echo $u['email']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_user" value="<?php echo $u['id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Delete this user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="admin-table-section">
            <h2>All Technicians</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Service</th><th>District</th><th>Phone</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($t = mysqli_fetch_assoc($techs)) { ?>
                        <tr>
                            <td><?php echo $t['id']; ?></td>
                            <td><?php echo $t['name']; ?></td>
                            <td><?php echo $t['email']; ?></td>
                            <td><?php echo $t['service_name']; ?></td>
                            <td><?php echo $t['district_name']; ?></td>
                            <td><?php echo $t['phone']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_tech" value="<?php echo $t['id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Delete this technician?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

</body>
</html>