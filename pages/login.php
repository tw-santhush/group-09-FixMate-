<?php
include '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, name, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: home.php');
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
            <h1>Login</h1>
            <p>Welcome back! Sign in to your account.</p>

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

            <p class="auth-footer">
                Don't have an account? <a href="register.php">Create one here</a>
            </p>

            <div class="auth-roles">
                <a href="login_technician.php">Technician Login</a>
                <span>|</span>
                <a href="login_admin.php">Admin Login</a>
            </div>
        </div>
    </div>
</section>

</body>
</html>