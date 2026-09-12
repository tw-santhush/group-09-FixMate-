<?php
include '../includes/config.php';
include '../includes/header.php';

// Read service and district from POST or GET
$district_id = $_POST['district'] ?? ($_GET['district'] ?? '');
$service_id  = $_POST['service']  ?? ($_GET['service']  ?? '');

$results = [];
$searched = false;

// Only search if a service is chosen
if ($service_id != '') {
    $sql = "SELECT t.id, t.name, t.phone, t.rating, t.jobs_completed, s.service_name, d.district_name
            FROM technicians t
            JOIN services s ON t.service_id = s.id
            JOIN districts d ON t.district_id = d.id
            WHERE t.service_id = '$service_id'";

    if ($district_id != '') {
        $sql .= " AND t.district_id = '$district_id'";
    }

    $sql .= " ORDER BY t.rating DESC";

    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
    }
    $searched = true;
}
?>

<section class="find-filters">
    <div class="find-container">
        <h1>Find a Service Pro</h1>

        <form method="POST" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label>District</label>
                    <select name="district">
                        <option value="">All Districts</option>
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

                <div class="filter-group">
                    <label>Service</label>
                    <select name="service" required>
                        <option value="">-- Choose a Service --</option>
                        <option value="1"  <?php if ($service_id == '1')  echo 'selected'; ?>>Plumber</option>
                        <option value="2"  <?php if ($service_id == '2')  echo 'selected'; ?>>Electrician</option>
                        <option value="3"  <?php if ($service_id == '3')  echo 'selected'; ?>>Carpenter</option>
                        <option value="4"  <?php if ($service_id == '4')  echo 'selected'; ?>>Mechanic</option>
                        <option value="5"  <?php if ($service_id == '5')  echo 'selected'; ?>>Painter</option>
                        <option value="6"  <?php if ($service_id == '6')  echo 'selected'; ?>>Welder</option>
                        <option value="7"  <?php if ($service_id == '7')  echo 'selected'; ?>>Mason</option>
                        <option value="8"  <?php if ($service_id == '8')  echo 'selected'; ?>>Locksmith</option>
                        <option value="9"  <?php if ($service_id == '9')  echo 'selected'; ?>>Appliance Repair</option>
                        <option value="10" <?php if ($service_id == '10') echo 'selected'; ?>>AC Technician</option>
                        <option value="11" <?php if ($service_id == '11') echo 'selected'; ?>>Phone Repair</option>
                        <option value="12" <?php if ($service_id == '12') echo 'selected'; ?>>Computer Technician</option>
                        <option value="13" <?php if ($service_id == '13') echo 'selected'; ?>>Plumbing & Gas</option>
                        <option value="14" <?php if ($service_id == '14') echo 'selected'; ?>>Electrical & Solar</option>
                        <option value="15" <?php if ($service_id == '15') echo 'selected'; ?>>General Repairs</option>
                    </select>
                </div>

                <button type="submit" class="search-btn">Search</button>
            </div>
        </form>
    </div>
</section>

<section class="find-results">
    <div class="find-container">
        <div id="results">
            <?php if ($searched) { ?>
                <?php if (count($results) > 0) { ?>
                    <?php foreach ($results as $worker) {
                        // Build international phone number (07x → 947x)
                        $phone = '94' . substr($worker['phone'], -9);
                    ?>
                        <div class="worker-card">
                            <h3><?php echo $worker['name']; ?></h3>
                            <span class="job-type"><?php echo $worker['service_name']; ?></span>

                            <div class="location">📍 <?php echo $worker['district_name']; ?></div>

                            <div class="rating">
                                ⭐ <?php echo number_format($worker['rating'], 1); ?> (<?php echo $worker['jobs_completed']; ?> jobs)
                            </div>

                            <div class="phone-display">📞 <?php echo $worker['phone']; ?></div>

                            <div class="contact-buttons">
                                <a href="tel:+<?php echo $phone; ?>" class="phone-btn">📞 Call</a>
                                <a href="https://wa.me/<?php echo $phone; ?>?text=Hi%20I%20need%20your%20service" target="_blank" class="whatsapp-btn">💬 WhatsApp</a>
                                <a href="view_reviews.php?tech_id=<?php echo $worker['id']; ?>" class="view-reviews-btn">👁️ Reviews</a>
                                <a href="add_review.php?tech_id=<?php echo $worker['id']; ?>" class="review-btn">⭐ Review</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p style="text-align: center; color: #6b7280; padding: 40px;">No technicians found. Try another district or service.</p>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>

</body>
</html>