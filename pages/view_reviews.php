<?php

/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

// Get technician ID from URL
$tech_id = isset($_GET['tech_id']) ? mysqli_real_escape_string($conn, $_GET['tech_id']) : 0;

// Fetch technician data
$tech_query = "SELECT t.id, t.name, t.rating, t.jobs_completed, s.service_name, d.district_name 
               FROM technicians t
               JOIN services s ON t.service_id = s.id
               JOIN districts d ON t.district_id = d.id
               WHERE t.id = '$tech_id'";
$tech_result = mysqli_query($conn, $tech_query);
$tech = mysqli_fetch_assoc($tech_result);

if (!$tech) {
    header('Location: find.php');
    exit();
}

// Fetch all reviews for this technician
$reviews_query = "SELECT r.id, r.rating, r.review, r.created_at, u.name as user_name
                  FROM ratings r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.technician_id = '$tech_id' AND r.review IS NOT NULL AND r.review != ''
                  ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_query);
$reviews = [];
while ($row = mysqli_fetch_assoc($reviews_result)) {
    $reviews[] = $row;
}

// Helper function to render stars
function renderStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '⭐';
        } else {
            $stars .= '☆';
        }
    }
    return $stars;
}
?>

<!-- View Reviews Section -->
<section class="reviews-section">
    <div class="reviews-container">
        <!-- Technician Header -->
        <div class="reviews-header">
            <div class="tech-info">
                <h1><?php echo htmlspecialchars($tech['name']); ?></h1>
                <p><?php echo htmlspecialchars($tech['service_name']); ?> • <?php echo htmlspecialchars($tech['district_name']); ?></p>
                <div class="tech-stats">
                    <span>⭐ <?php echo number_format($tech['rating'], 1); ?>/5.0</span>
                    <span>•</span>
                    <span><?php echo $tech['jobs_completed']; ?> jobs completed</span>
                </div>
            </div>
            <a href="find.php" class="back-btn">← Back to Search</a>
        </div>
        
        <!-- Reviews List -->
        <div class="reviews-list">
            <h2>Customer Reviews (<?php echo count($reviews); ?>)</h2>
            
            <?php if (count($reviews) > 0): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <div class="review-user"><?php echo htmlspecialchars($review['user_name']); ?></div>
                                <div class="review-rating"><?php echo renderStars($review['rating']); ?> <?php echo $review['rating']; ?>/5</div>
                            </div>
                            <div class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></div>
                        </div>
                        <div class="review-text">
                            <?php echo htmlspecialchars($review['review']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to review this professional!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

</body>
</html>