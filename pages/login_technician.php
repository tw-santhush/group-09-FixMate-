<?php
/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if ($email && $password) {
        $query = "SELECT id, name, password FROM technicians WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        $tech = mysqli_fetch_assoc($result);
        
        if ($tech && password_verify($password, $tech['password'])) {
            $_SESSION['tech_id'] = $tech['id'];
            $_SESSION['tech_name'] = $tech['name'];
            $_SESSION['user_type'] = 'technician';
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill all fields.';
    }
}
?>

<!-- Technician Login Section -->
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Technician Login</h1>
            <p>Sign in to your service professional account.</p>
            
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
                Don't have an account? <a href="register_technician.php">Register here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>