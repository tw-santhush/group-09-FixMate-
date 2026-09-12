<?php
include '../includes/config.php';
include '../includes/header.php';

$tech_id = $_GET['tech_id'];

$tech_query = "SELECT t.id, t.name, t.rating, t.jobs_completed, s.service_name, d.district_name 
               FROM technicians t
               JOIN services s ON t.service_id = s.id
               JOIN districts d ON t.district_id = d.id
               WHERE t.id = '$tech_id'";
$tech = mysqli_fetch_assoc(mysqli_query($conn, $tech_query));

if (!$tech) {
    header('Location: find.php');
    exit();
}

$reviews_query = "SELECT r.rating, r.review, r.created_at, u.name AS user_name
                  FROM ratings r
                  JOIN users u ON r.user_id = u.id
                  WHERE r.technician_id = '$tech_id' AND r.review != ''
                  ORDER BY r.created_at DESC";
$reviews = mysqli_query($conn, $reviews_query);
?>

<section class="reviews-section">
    <div class="reviews-container">
        <div class="reviews-header">
            <div class="tech-info">
                <h1><?php echo $tech['name']; ?></h1>
                <p><?php echo $tech['service_name']; ?> • <?php echo $tech['district_name']; ?></p>
                <div class="tech-stats">
                    ⭐ <?php echo number_format($tech['rating'], 1); ?>/5.0 • <?php echo $tech['jobs_completed']; ?> jobs
                </div>
            </div>
            <a href="find.php" class="back-btn">← Back to Search</a>
        </div>

        <div class="reviews-list">
            <h2>Customer Reviews (<?php echo mysqli_num_rows($reviews); ?>)</h2>

            <?php if (mysqli_num_rows($reviews) > 0) { ?>
                <?php while ($r = mysqli_fetch_assoc($reviews)) { ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div>
                                <div class="review-user"><?php echo $r['user_name']; ?></div>
                                <div class="review-rating"><?php echo $r['rating']; ?>/5</div>
                            </div>
                            <div class="review-date"><?php echo date('M d, Y', strtotime($r['created_at'])); ?></div>
                        </div>
                        <div class="review-text"><?php echo $r['review']; ?></div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to review this professional!</p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

</body>
</html>