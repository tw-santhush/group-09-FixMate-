<?php

/** @var mysqli $conn */

include '../includes/config.php';
include '../includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=find.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get technician ID from URL
$tech_id = isset($_GET['tech_id']) ? mysqli_real_escape_string($conn, $_GET['tech_id']) : 0;

// Fetch technician data
$tech_query = "SELECT t.id, t.name, s.service_name FROM technicians t 
               JOIN services s ON t.service_id = s.id 
               WHERE t.id = '$tech_id'";
$tech_result = mysqli_query($conn, $tech_query);
$tech = mysqli_fetch_assoc($tech_result);

if (!$tech) {
    header('Location: find.php');
    exit();
}

// Check if user already reviewed this technician
$check_query = "SELECT id FROM ratings WHERE user_id = '$user_id' AND technician_id = '$tech_id'";
$check_result = mysqli_query($conn, $check_query);
$existing_review = mysqli_fetch_assoc($check_result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $review = mysqli_real_escape_string($conn, $_POST['review']);
    
    if ($rating >= 1 && $rating <= 5) {
        if ($existing_review) {
            // Update existing review
            $update_query = "UPDATE ratings SET rating = '$rating', review = '$review' 
                            WHERE user_id = '$user_id' AND technician_id = '$tech_id'";
            if (mysqli_query($conn, $update_query)) {
                $success = 'Review updated successfully!';
            } else {
                $error = 'Error updating review. Please try again.';
            }
        } else {
            // Insert new review
            $insert_query = "INSERT INTO ratings (user_id, technician_id, rating, review) 
                            VALUES ('$user_id', '$tech_id', '$rating', '$review')";
            if (mysqli_query($conn, $insert_query)) {
                $success = 'Review submitted successfully!';
            } else {
                $error = 'Error submitting review. Please try again.';
            }
        }
        
        // Recalculate technician's average rating
        $avg_query = "SELECT AVG(rating) as avg_rating FROM ratings WHERE technician_id = '$tech_id'";
        $avg_result = mysqli_query($conn, $avg_query);
        $avg_rating = mysqli_fetch_assoc($avg_result)['avg_rating'];
        
        $update_tech = "UPDATE technicians SET rating = '$avg_rating' WHERE id = '$tech_id'";
        mysqli_query($conn, $update_tech);
    } else {
        $error = 'Please select a valid rating (1-5 stars).';
    }
}

// Get existing review if any
if ($existing_review) {
    $review_query = "SELECT * FROM ratings WHERE user_id = '$user_id' AND technician_id = '$tech_id'";
    $review_result = mysqli_query($conn, $review_query);
    $existing = mysqli_fetch_assoc($review_result);
}
?>

<!-- Review Form Section -->
<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Leave a Review</h1>
            <p>Rate <?php echo htmlspecialchars($tech['name']); ?> (<?php echo htmlspecialchars($tech['service_name']); ?>)</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?> <a href="find.php">Back to search</a></div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="rating">Your Rating (1-5 stars)</label>
                    <div class="rating-selector">
                        <input type="radio" id="star5" name="rating" value="5" <?php echo (isset($existing) && $existing['rating'] == 5) ? 'checked' : ''; ?> required>
                        <label for="star5" title="5 Stars">⭐⭐⭐⭐⭐ Excellent</label>
                        
                        <input type="radio" id="star4" name="rating" value="4" <?php echo (isset($existing) && $existing['rating'] == 4) ? 'checked' : ''; ?>>
                        <label for="star4" title="4 Stars">⭐⭐⭐⭐ Good</label>
                        
                        <input type="radio" id="star3" name="rating" value="3" <?php echo (isset($existing) && $existing['rating'] == 3) ? 'checked' : ''; ?>>
                        <label for="star3" title="3 Stars">⭐⭐⭐ Average</label>
                        
                        <input type="radio" id="star2" name="rating" value="2" <?php echo (isset($existing) && $existing['rating'] == 2) ? 'checked' : ''; ?>>
                        <label for="star2" title="2 Stars">⭐⭐ Poor</label>
                        
                        <input type="radio" id="star1" name="rating" value="1" <?php echo (isset($existing) && $existing['rating'] == 1) ? 'checked' : ''; ?>>
                        <label for="star1" title="1 Star">⭐ Very Poor</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="review">Your Review (optional)</label>
                    <textarea id="review" name="review" rows="5" placeholder="Share your experience with this service professional..."><?php echo isset($existing) ? htmlspecialchars($existing['review']) : ''; ?></textarea>
                </div>
                
                <div style="display: flex; gap: 12px;">
                    <button type="submit" name="submit_review" class="auth-button" style="flex: 1;">Submit Review</button>
                    <a href="find.php" class="cancel-btn" style="flex: 1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

</body>
</html>