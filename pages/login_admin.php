<?php
include '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, email, password FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: admin_panel.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}

include '../includes/header.php';
?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Admin Login</h1>
            <p>Sign in to the FixMate admin panel.</p>

            <?php if ($error) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="auth-button">Sign In</button>
            </form>
        </div>
    </div>
</section>

</body>
</html>