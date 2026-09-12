<?php
include '../includes/config.php';
include '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT id, name, password FROM technicians WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $tech = mysqli_fetch_assoc($result);

    if ($tech && password_verify($password, $tech['password'])) {
        $_SESSION['tech_id'] = $tech['id'];
        $_SESSION['tech_name'] = $tech['name'];
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Technician Login</h1>
            <p>Sign in to your service professional account.</p>

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
                Don't have an account? <a href="register_technician.php">Register here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>