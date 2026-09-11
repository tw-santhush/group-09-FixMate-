<?php
/** @var mysqli $conn */

include '../includes/config.php';

$error = '';

// Handle form submission BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if ($email && $password) {
        $query = "SELECT id, name, password FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_type'] = 'normal';
            header('Location: home.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill all fields.';
    }
}

// Now include header (HTML output starts here)
include '../includes/header.php';
?>

<!-- Normal User Login Section -->
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Login</h1>
            <p>Welcome back! Sign in to your account.</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="auth-button">Sign In</button>
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