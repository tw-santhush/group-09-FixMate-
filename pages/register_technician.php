<?php
include '../includes/config.php';
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_input = $_POST['phone'];
    // Convert 0712345678 to 94712345678
    $phone = '94' . substr($phone_input, 1);
    $district_id = $_POST['district'];
    $service_id = $_POST['service'];
    $experience = $_POST['experience'];
    $bio = $_POST['bio'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = mysqli_query($conn, "SELECT id FROM technicians WHERE email = '$email'");

        if (mysqli_num_rows($check) > 0) {
            $error = 'Email already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO technicians (name, email, phone, district_id, service_id, experience, bio, password) 
                       VALUES ('$name', '$email', '$phone', '$district_id', '$service_id', '$experience', '$bio', '$hashed')";

            if (mysqli_query($conn, $insert)) {
                $success = 'Account created! <a href="login_technician.php">Click here to login</a>';
            } else {
                $error = 'Error creating account.';
            }
        }
    }
}
?>

<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Register as a Service Pro</h1>
            <p>Get listed on FixMate and start receiving customer calls.</p>

            <?php if ($error) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php } ?>

            <form method="POST" class="auth-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" placeholder="0712345678" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>District</label>
                        <select name="district" required>
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
                            <option value="22">Matale</option>
                            <option value="23">Kilinochchi</option>
                            <option value="24">Mannar</option>
                            <option value="25">Vavuniya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Service Type</label>
                        <select name="service" required>
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
                    <label>Years of Experience</label>
                    <input type="number" name="experience" min="0" max="70" required>
                </div>

                <div class="form-group">
                    <label>Short Bio</label>
                    <textarea name="bio" rows="4" placeholder="Tell customers about your experience..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                </div>

                <button type="submit" class="auth-button">Create Account</button>
            </form>

            <p class="auth-footer">
                Already have an account? <a href="login_technician.php">Sign in here</a>
            </p>
        </div>
    </div>
</section>

</body>
</html>