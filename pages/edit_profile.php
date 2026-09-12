<?php
/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

// Check if technician is logged in
if (!isset($_SESSION['tech_id'])) {
    header('Location: login_technician.php');
    exit();
}

$tech_id = $_SESSION['tech_id'];
$error = '';
$success = '';

// Fetch current technician data
$query = "SELECT * FROM technicians WHERE id = '$tech_id'";
$result = mysqli_query($conn, $query);
$tech = mysqli_fetch_assoc($result);

if (!$tech) {
    header('Location: login_technician.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $district_id = mysqli_real_escape_string($conn, $_POST['district']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    
    if ($name && $phone && $district_id && $service_id && $experience) {
        $update_query = "UPDATE technicians SET 
                        name = '$name', 
                        phone = '$phone', 
                        district_id = '$district_id', 
                        service_id = '$service_id', 
                        experience = '$experience', 
                        bio = '$bio'
                        WHERE id = '$tech_id'";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['tech_name'] = $name;
            $success = 'Profile updated successfully! <a href="dashboard.php">Back to dashboard</a>';
            $tech = array_merge($tech, $_POST);
        } else {
            $error = 'Error updating profile. Please try again.';
        }
    } else {
        $error = 'Please fill all required fields.';
    }
}
?>

<!-- Edit Profile Section -->
<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Edit Your Profile</h1>
            <p>Update your service professional information.</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?> <a href="dashboard.php">Back to dashboard</a></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($tech['name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($tech['phone']); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="district">District</label>
                        <select id="district" name="district" required>
                            <option value="">-- Choose District --</option>
                            <option value="1"  <?php echo ($tech['district_id'] == 1)  ? 'selected' : ''; ?>>Colombo</option>
                            <option value="2"  <?php echo ($tech['district_id'] == 2)  ? 'selected' : ''; ?>>Gampaha</option>
                            <option value="3"  <?php echo ($tech['district_id'] == 3)  ? 'selected' : ''; ?>>Kalutara</option>
                            <option value="4"  <?php echo ($tech['district_id'] == 4)  ? 'selected' : ''; ?>>Kandy</option>
                            <option value="5"  <?php echo ($tech['district_id'] == 5)  ? 'selected' : ''; ?>>Matara</option>
                            <option value="6"  <?php echo ($tech['district_id'] == 6)  ? 'selected' : ''; ?>>Galle</option>
                            <option value="7"  <?php echo ($tech['district_id'] == 7)  ? 'selected' : ''; ?>>Hambantota</option>
                            <option value="8"  <?php echo ($tech['district_id'] == 8)  ? 'selected' : ''; ?>>Jaffna</option>
                            <option value="9"  <?php echo ($tech['district_id'] == 9)  ? 'selected' : ''; ?>>Mullaitivu</option>
                            <option value="10" <?php echo ($tech['district_id'] == 10) ? 'selected' : ''; ?>>Batticaloa</option>
                            <option value="11" <?php echo ($tech['district_id'] == 11) ? 'selected' : ''; ?>>Ampara</option>
                            <option value="12" <?php echo ($tech['district_id'] == 12) ? 'selected' : ''; ?>>Trincomalee</option>
                            <option value="13" <?php echo ($tech['district_id'] == 13) ? 'selected' : ''; ?>>Kurunegala</option>
                            <option value="14" <?php echo ($tech['district_id'] == 14) ? 'selected' : ''; ?>>Puttalam</option>
                            <option value="15" <?php echo ($tech['district_id'] == 15) ? 'selected' : ''; ?>>Anuradhapura</option>
                            <option value="16" <?php echo ($tech['district_id'] == 16) ? 'selected' : ''; ?>>Polonnaruwa</option>
                            <option value="17" <?php echo ($tech['district_id'] == 17) ? 'selected' : ''; ?>>Badulla</option>
                            <option value="18" <?php echo ($tech['district_id'] == 18) ? 'selected' : ''; ?>>Monaragala</option>
                            <option value="19" <?php echo ($tech['district_id'] == 19) ? 'selected' : ''; ?>>Ratnapura</option>
                            <option value="20" <?php echo ($tech['district_id'] == 20) ? 'selected' : ''; ?>>Kegalle</option>
                            <option value="21" <?php echo ($tech['district_id'] == 21) ? 'selected' : ''; ?>>Nuwara Eliya</option>
                            <option value="22" <?php echo ($tech['district_id'] == 22) ? 'selected' : ''; ?>>Matale</option>
                            <option value="23" <?php echo ($tech['district_id'] == 23) ? 'selected' : ''; ?>>Kilinochchi</option>
                            <option value="24" <?php echo ($tech['district_id'] == 24) ? 'selected' : ''; ?>>Mannar</option>
                            <option value="25" <?php echo ($tech['district_id'] == 25) ? 'selected' : ''; ?>>Vavuniya</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="service">Service Type</label>
                        <select id="service" name="service" required>
                            <option value="">-- Choose Service --</option>
                            <option value="1" <?php echo ($tech['service_id'] == 1) ? 'selected' : ''; ?>>Plumber</option>
                            <option value="2" <?php echo ($tech['service_id'] == 2) ? 'selected' : ''; ?>>Electrician</option>
                            <option value="3" <?php echo ($tech['service_id'] == 3) ? 'selected' : ''; ?>>Carpenter</option>
                            <option value="4" <?php echo ($tech['service_id'] == 4) ? 'selected' : ''; ?>>Mechanic</option>
                            <option value="5" <?php echo ($tech['service_id'] == 5) ? 'selected' : ''; ?>>Painter</option>
                            <option value="6" <?php echo ($tech['service_id'] == 6) ? 'selected' : ''; ?>>Welder</option>
                            <option value="7" <?php echo ($tech['service_id'] == 7) ? 'selected' : ''; ?>>Mason</option>
                            <option value="8" <?php echo ($tech['service_id'] == 8) ? 'selected' : ''; ?>>Locksmith</option>
                            <option value="9" <?php echo ($tech['service_id'] == 9) ? 'selected' : ''; ?>>Appliance Repair</option>
                            <option value="10" <?php echo ($tech['service_id'] == 10) ? 'selected' : ''; ?>>AC Technician</option>
                            <option value="11" <?php echo ($tech['service_id'] == 11) ? 'selected' : ''; ?>>Phone Repair</option>
                            <option value="12" <?php echo ($tech['service_id'] == 12) ? 'selected' : ''; ?>>Computer Technician</option>
                            <option value="13" <?php echo ($tech['service_id'] == 13) ? 'selected' : ''; ?>>Plumbing & Gas</option>
                            <option value="14" <?php echo ($tech['service_id'] == 14) ? 'selected' : ''; ?>>Electrical & Solar</option>
                            <option value="15" <?php echo ($tech['service_id'] == 15) ? 'selected' : ''; ?>>General Repairs</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="experience">Years of Experience</label>
                    <input type="number" id="experience" name="experience" value="<?php echo $tech['experience']; ?>" min="0" max="70" required>
                </div>
                
                <div class="form-group">
                    <label for="bio">Short Bio / About You</label>
                    <textarea id="bio" name="bio" rows="4"><?php echo htmlspecialchars($tech['bio']); ?></textarea>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" name="update" class="auth-button" style="flex: 1;">Save Changes</button>
                    <a href="dashboard.php" class="cancel-btn" style="flex: 1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

</body>
</html>