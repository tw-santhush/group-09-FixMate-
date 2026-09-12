<?php
include '../includes/config.php';
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

        if (mysqli_num_rows($check) > 0) {
            $error = 'Email already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed')";

            if (mysqli_query($conn, $insert)) {
                $success = 'Account created! <a href="login.php">Click here to login</a>';
            } else {
                $error = 'Error creating account.';
            }
        }
    }
}
?>

<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Create Account</h1>
            <p>Join FixMate to find local service professionals.</p>

            <?php if ($error) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php } ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="auth-button">Create Account</button>
            </form>

            <p class="auth-footer">
                Already have an account? <a href="login.php">Sign in here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>