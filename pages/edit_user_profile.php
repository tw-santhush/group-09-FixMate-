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
$error = '';
$success = '';

// Fetch current user data
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header('Location: login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    if ($name) {
        $update_query = "UPDATE users SET 
                        name = '$name', 
                        phone = '$phone'
                        WHERE id = '$user_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully! <a href="user_profile.php">Back to profile</a>';
            $user['name'] = $name;
            $user['phone'] = $phone;
        } else {
            $error = 'Error updating profile. Please try again.';
        }
    } else {
        $error = 'Please fill all required fields.';
    }
}
?>

<!-- Edit User Profile Section -->
<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Edit Your Profile</h1>
            <p>Update your account information.</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?> <a href="user_profile.php">Back to profile</a></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address (cannot change)</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Optional">
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" name="update" class="auth-button" style="flex: 1;">Save Changes</button>
                    <a href="user_profile.php" class="cancel-btn" style="flex: 1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

</body>
</html>