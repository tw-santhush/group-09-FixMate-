<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    $update = "UPDATE users SET name = '$name', phone = '$phone' WHERE id = '$user_id'";

    if (mysqli_query($conn, $update)) {
        $_SESSION['user_name'] = $name;
        $success = 'Profile updated! <a href="user_profile.php">Back to profile</a>';
        $user['name'] = $name;
        $user['phone'] = $phone;
    } else {
        $error = 'Error updating profile.';
    }
}
?>

<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Edit Your Profile</h1>
            <p>Update your account information.</p>

            <?php if ($error) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php } ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email (cannot change)</label>
                    <input type="email" value="<?php echo $user['email']; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" value="<?php echo $user['phone']; ?>">
                </div>

                <div class="button-row">
                    <button type="submit" class="auth-button">Save Changes</button>
                    <a href="user_profile.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

</body>
</html>