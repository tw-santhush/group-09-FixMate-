<?php
/** @var mysqli $conn */
include '../includes/config.php';
include '../includes/header.php';

$results = [];
$searched = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $district_id = mysqli_real_escape_string($conn, $_POST['district']);
    $service_id = mysqli_real_escape_string($conn, $_POST['service']);
    
    if ($district_id && $service_id) {
        $query = "SELECT t.id, t.name, t.phone, t.rating, t.jobs_completed, 
                         s.service_name, d.district_name 
                  FROM technicians t
                  JOIN services s ON t.service_id = s.id
                  JOIN districts d ON t.district_id = d.id
                  WHERE t.district_id = '$district_id' AND t.service_id = '$service_id'
                  ORDER BY t.rating DESC";
        
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $results[] = $row;
            }
        }
        
        $searched = true;
    }
}
?>

<!-- Filter Section -->
<section class="find-filters">
    <div class="find-container">
        <h1>Find a Service Pro</h1>
        
        <form method="POST" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="district">Select District:</label>
                    <select id="district" name="district" required>
                        <option value="">-- Choose a District --</option>
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
                
                <div class="filter-group">
                    <label for="service">Select Service:</label>
                    <select id="service" name="service" required>
                        <option value="">-- Choose a Service --</option>
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
                
                <button type="submit" class="search-btn">Search</button>
            </div>
        </form>
    </div>
</section>

<!-- Results Section (placeholder for now) -->
<section class="find-results">
    <div class="find-container">
        <div id="results">
    <?php if ($searched): ?>
        <?php if (count($results) > 0): ?>
            <?php foreach ($results as $worker): ?>
                <div class="worker-card">
                    <h3><?php echo htmlspecialchars($worker['name']); ?></h3>
                    <span class="job-type"><?php echo htmlspecialchars($worker['service_name']); ?></span>
                    
                    <div class="location">
                        📍 <?php echo htmlspecialchars($worker['district_name']); ?>
                    </div>
                    
                    <div class="rating">
                        ⭐ <?php echo number_format($worker['rating'], 1); ?> (<?php echo $worker['jobs_completed']; ?> jobs)
                    </div>

                    <div class="phone-display">
                        📞 <?php echo htmlspecialchars($worker['phone']); ?>
                    </div>

                    
                    <div class="contact-buttons">
                        <?php 
                        $phone_clean = preg_replace('/[^0-9]/', '', $worker['phone']);
                        // If phone already starts with 94, use as-is; otherwise prepend 94
                        if (substr($phone_clean, 0, 2) == '94') {
                            $phone_intl = $phone_clean;
                        } else if (substr($phone_clean, 0, 1) == '0') {
                            $phone_intl = '94' . substr($phone_clean, 1);
                        } else {
                            $phone_intl = '94' . $phone_clean;
                        }
                        ?>
                        <a href="tel:+<?php echo $phone_intl; ?>" class="phone-btn">
                            📞 Call
                        </a>
                        <a href="https://wa.me/<?php echo $phone_intl; ?>?text=Hi%20I%20need%20your%20service" target="_blank" class="whatsapp-btn">
                            💬 WhatsApp
                        </a>
                        <a href="view_reviews.php?tech_id=<?php echo $worker['id']; ?>" class="view-reviews-btn">
                            👁️ Reviews
                        </a>
                        <a href="add_review.php?tech_id=<?php echo $worker['id']; ?>" class="review-btn">
                            ⭐ Review
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: #6b7280; padding: 40px;">No technicians found. Try another district or service.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
    </div>
</section>

</body>
</html>