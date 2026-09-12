<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['tech_id'])) {
    header('Location: login_technician.php');
    exit();
}

$tech_id = $_SESSION['tech_id'];
$error = '';
$success = '';

$query = "SELECT * FROM technicians WHERE id = '$tech_id'";
$result = mysqli_query($conn, $query);
$tech = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $district_id = $_POST['district'];
    $service_id = $_POST['service'];
    $experience = $_POST['experience'];
    $bio = $_POST['bio'];

    $update = "UPDATE technicians SET 
                name = '$name',
                phone = '$phone',
                district_id = '$district_id',
                service_id = '$service_id',
                experience = '$experience',
                bio = '$bio'
                WHERE id = '$tech_id'";

    if (mysqli_query($conn, $update)) {
        $_SESSION['tech_name'] = $name;
        $success = 'Profile updated! <a href="dashboard.php">Back to dashboard</a>';
        $tech = array_merge($tech, $_POST);
    } else {
        $error = 'Error updating profile.';
    }
}
?>

<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Edit Your Profile</h1>
            <p>Update your service professional information.</p>

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
                        <input type="text" name="name" value="<?php echo $tech['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" value="<?php echo $tech['phone']; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>District</label>
                        <select name="district" required>
                            <option value="1" <?php if ($tech['district_id'] == 1) echo 'selected'; ?>>Colombo</option>
                            <option value="2" <?php if ($tech['district_id'] == 2) echo 'selected'; ?>>Gampaha</option>
                            <option value="3" <?php if ($tech['district_id'] == 3) echo 'selected'; ?>>Kalutara</option>
                            <option value="4" <?php if ($tech['district_id'] == 4) echo 'selected'; ?>>Kandy</option>
                            <option value="5" <?php if ($tech['district_id'] == 5) echo 'selected'; ?>>Matara</option>
                            <option value="6" <?php if ($tech['district_id'] == 6) echo 'selected'; ?>>Galle</option>
                            <option value="7" <?php if ($tech['district_id'] == 7) echo 'selected'; ?>>Hambantota</option>
                            <option value="8" <?php if ($tech['district_id'] == 8) echo 'selected'; ?>>Jaffna</option>
                            <option value="9" <?php if ($tech['district_id'] == 9) echo 'selected'; ?>>Mullaitivu</option>
                            <option value="10" <?php if ($tech['district_id'] == 10) echo 'selected'; ?>>Batticaloa</option>
                            <option value="11" <?php if ($tech['district_id'] == 11) echo 'selected'; ?>>Ampara</option>
                            <option value="12" <?php if ($tech['district_id'] == 12) echo 'selected'; ?>>Trincomalee</option>
                            <option value="13" <?php if ($tech['district_id'] == 13) echo 'selected'; ?>>Kurunegala</option>
                            <option value="14" <?php if ($tech['district_id'] == 14) echo 'selected'; ?>>Puttalam</option>
                            <option value="15" <?php if ($tech['district_id'] == 15) echo 'selected'; ?>>Anuradhapura</option>
                            <option value="16" <?php if ($tech['district_id'] == 16) echo 'selected'; ?>>Polonnaruwa</option>
                            <option value="17" <?php if ($tech['district_id'] == 17) echo 'selected'; ?>>Badulla</option>
                            <option value="18" <?php if ($tech['district_id'] == 18) echo 'selected'; ?>>Monaragala</option>
                            <option value="19" <?php if ($tech['district_id'] == 19) echo 'selected'; ?>>Ratnapura</option>
                            <option value="20" <?php if ($tech['district_id'] == 20) echo 'selected'; ?>>Kegalle</option>
                            <option value="21" <?php if ($tech['district_id'] == 21) echo 'selected'; ?>>Nuwara Eliya</option>
                            <option value="22" <?php if ($tech['district_id'] == 22) echo 'selected'; ?>>Matale</option>
                            <option value="23" <?php if ($tech['district_id'] == 23) echo 'selected'; ?>>Kilinochchi</option>
                            <option value="24" <?php if ($tech['district_id'] == 24) echo 'selected'; ?>>Mannar</option>
                            <option value="25" <?php if ($tech['district_id'] == 25) echo 'selected'; ?>>Vavuniya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Service Type</label>
                        <select name="service" required>
                            <option value="1" <?php if ($tech['service_id'] == 1) echo 'selected'; ?>>Plumber</option>
                            <option value="2" <?php if ($tech['service_id'] == 2) echo 'selected'; ?>>Electrician</option>
                            <option value="3" <?php if ($tech['service_id'] == 3) echo 'selected'; ?>>Carpenter</option>
                            <option value="4" <?php if ($tech['service_id'] == 4) echo 'selected'; ?>>Mechanic</option>
                            <option value="5" <?php if ($tech['service_id'] == 5) echo 'selected'; ?>>Painter</option>
                            <option value="6" <?php if ($tech['service_id'] == 6) echo 'selected'; ?>>Welder</option>
                            <option value="7" <?php if ($tech['service_id'] == 7) echo 'selected'; ?>>Mason</option>
                            <option value="8" <?php if ($tech['service_id'] == 8) echo 'selected'; ?>>Locksmith</option>
                            <option value="9" <?php if ($tech['service_id'] == 9) echo 'selected'; ?>>Appliance Repair</option>
                            <option value="10" <?php if ($tech['service_id'] == 10) echo 'selected'; ?>>AC Technician</option>
                            <option value="11" <?php if ($tech['service_id'] == 11) echo 'selected'; ?>>Phone Repair</option>
                            <option value="12" <?php if ($tech['service_id'] == 12) echo 'selected'; ?>>Computer Technician</option>
                            <option value="13" <?php if ($tech['service_id'] == 13) echo 'selected'; ?>>Plumbing & Gas</option>
                            <option value="14" <?php if ($tech['service_id'] == 14) echo 'selected'; ?>>Electrical & Solar</option>
                            <option value="15" <?php if ($tech['service_id'] == 15) echo 'selected'; ?>>General Repairs</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Years of Experience</label>
                    <input type="number" name="experience" value="<?php echo $tech['experience']; ?>" min="0" max="70" required>
                </div>

                <div class="form-group">
                    <label>Short Bio</label>
                    <textarea name="bio" rows="4"><?php echo $tech['bio']; ?></textarea>
                </div>

                <div class="button-row">
                    <button type="submit" class="auth-button">Save Changes</button>
                    <a href="dashboard.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

</body>
</html>