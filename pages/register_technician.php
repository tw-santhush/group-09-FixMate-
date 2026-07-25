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
    $phone_input = mysqli_real_escape_string($conn, $_POST['phone']);
    // Normalize: remove +, 0, spaces; ensure starts with 94
    $phone = preg_replace('/[^0-9]/', '', $phone_input);
    if (substr($phone, 0, 2) == '94') {
        // Already correct, starts with 94
    } else if (substr($phone, 0, 1) == '0') {
        $phone = '94' . substr($phone, 1); // Remove leading 0, add 94
    } else {
        $phone = '94' . $phone; // No 0, just prepend 94
    }
    $district_id = mysqli_real_escape_string($conn, $_POST['district']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($name && $email && $phone && $district_id && $service_id && $experience && $password && $confirm_password) {
        // Check if password matches
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match.';
        } else if (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } else {
            // Check if email already exists
            $check_query = "SELECT id FROM technicians WHERE email = '$email'";
            $check_result = mysqli_query($conn, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                $error = 'Email already registered as a technician.';
            } else {
                // Hash password and insert technician
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_query = "INSERT INTO technicians (name, email, phone, district_id, service_id, experience, bio, password) 
                                 VALUES ('$name', '$email', '$phone', '$district_id', '$service_id', '$experience', '$bio', '$hashed_password')";
                
                if (mysqli_query($conn, $insert_query)) {
                    $success = 'Account created successfully! Your phone: ' . htmlspecialchars($phone) . ' <a href="login_technician.php">Click here to login</a>';
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

<!-- Technician Registration Section -->
<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Register as a Service Pro</h1>
            <p>Get listed on FixMate and start receiving customer calls.</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="0712345678" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="district">District</label>
                        <select id="district" name="district" required>
                            <option value="">-- Choose District --</option>
                            <option value="1">Colombo</option>
                            <option value="2">Gampaha</option>
                            <option value="3">Kalutara</option>
                            <option value="4">Kandy</option>
                            <option value="5">Matara</option>
                            <option value="6">Galle</option>
                            <option value="7">Hambantota</option>
                            <option value="8">Jaffna</option>
                            <option value="9">Mullaitivu</option>
                            <option value="10">Batticaloa</option>
                            <option value="11">Ampara</option>
                            <option value="12">Trincomalee</option>
                            <option value="13">Kurunegala</option>
                            <option value="14">Puttalam</option>
                            <option value="15">Anuradhapura</option>
                            <option value="16">Polonnaruwa</option>
                            <option value="17">Badulla</option>
                            <option value="18">Monaragala</option>
                            <option value="19">Ratnapura</option>
                            <option value="20">Kegalle</option>
                            <option value="21">Nuwara Eliya</option>
                            <option value="22">Matara</option>
                            <option value="23">Kalutara</option>
                            <option value="24">Chilaw</option>
                            <option value="25">Negombo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="service">Service Type</label>
                        <select id="service" name="service" required>
                            <option value="">-- Choose Service --</option>
                            <option value="1">Plumber</option>
                            <option value="2">Electrician</option>
                            <option value="3">Carpenter</option>
                            <option value="4">Mechanic</option>
                            <option value="5">Painter</option>
                            <option value="6">Welder</option>
                            <option value="7">Mason</option>
                            <option value="8">Locksmith</option>
                            <option value="9">Appliance Repair</option>
                            <option value="10">AC Technician</option>
                            <option value="11">Phone Repair</option>
                            <option value="12">Computer Technician</option>
                            <option value="13">Plumbing & Gas</option>
                            <option value="14">Electrical & Solar</option>
                            <option value="15">General Repairs</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="experience">Years of Experience</label>
                    <input type="number" id="experience" name="experience" min="0" max="70" required>
                </div>
                
                <div class="form-group">
                    <label for="bio">Short Bio / About You</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell customers about your experience and specialties..."></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                
                <button type="submit" name="register" class="auth-button">Create Account</button>
            </form>
            
            <p class="auth-footer">
                Already have an account? <a href="login_technician.php">Sign in here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>