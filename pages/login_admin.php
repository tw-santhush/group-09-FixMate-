<?php

/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if ($email && $password) {
    $query = "SELECT id, email, password FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);
    
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['user_type'] = 'admin';
            header('Location: admin_panel.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill all fields.';
    }
}
?>

<!-- Admin Login Section -->
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Admin Login</h1>
            <p>Sign in to the FixMate admin panel.</p>
            
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
        </div>
    </div>
</section>

</body>
</html>