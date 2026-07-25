<?php
/** @var mysqli $conn */
include '../includes/config.php';
include '../includes/header.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($name && $email && $password && $confirm_password) {
        // Check if password matches
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else if (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            // Check if email already exists
            $check_query = "SELECT id FROM users WHERE email = '$email'";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                $error = 'Email already registered.';
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
                
                if (mysqli_query($conn, $insert_query)) {
                    $success = 'Account created! <a href="login.php">Click here to login</a>';
                } else {
                    $error = 'Error creating account. Please try again.';
                }
            }
        }
    } else {
        $error = 'Please fill all fields.';
    }
}
?>

<!-- Registration Section -->
<section class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Create Account</h1>
            <p>Join FixMate to find local service professionals.</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" name="register" class="auth-button">Create Account</button>
            </form>
            
            <p class="auth-footer">
                Already have an account? <a href="login.php">Sign in here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>