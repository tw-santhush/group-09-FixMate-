<?php
include '../includes/config.php';
include '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$tech_id = $_GET['tech_id'];

$tech_query = "SELECT t.id, t.name, s.service_name FROM technicians t 
               JOIN services s ON t.service_id = s.id 
               WHERE t.id = '$tech_id'";
$tech = mysqli_fetch_assoc(mysqli_query($conn, $tech_query));

if (!$tech) {
    header('Location: find.php');
    exit();
}

$check = mysqli_query($conn, "SELECT * FROM ratings WHERE user_id = '$user_id' AND technician_id = '$tech_id'");
$existing = mysqli_fetch_assoc($check);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    if ($existing) {
        $sql = "UPDATE ratings SET rating = '$rating', review = '$review' 
                WHERE user_id = '$user_id' AND technician_id = '$tech_id'";
    } else {
        $sql = "INSERT INTO ratings (user_id, technician_id, rating, review) 
                VALUES ('$user_id', '$tech_id', '$rating', '$review')";
    }

    if (mysqli_query($conn, $sql)) {
        // Recalculate average rating
        $avg_query = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE technician_id = '$tech_id'";
        $avg = mysqli_fetch_assoc(mysqli_query($conn, $avg_query))['avg_rating'];
        mysqli_query($conn, "UPDATE technicians SET rating = '$avg' WHERE id = '$tech_id'");

        $success = 'Review saved! <a href="find.php">Back to search</a>';
    } else {
        $error = 'Error saving review.';
    }
}
?>

<section class="auth-section">
    <div class="auth-container auth-large">
        <div class="auth-box">
            <h1>Leave a Review</h1>
            <p>Rate <?php echo $tech['name']; ?> (<?php echo $tech['service_name']; ?>)</p>

            <?php if ($error) { ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php } ?>

            <?php if ($success) { ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php } ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Your Rating</label>
                    <div class="rating-selector">
                        <input type="radio" id="star5" name="rating" value="5" required>
                        <label for="star5">⭐⭐⭐⭐⭐ Excellent</label>

                        <input type="radio" id="star4" name="rating" value="4">
                        <label for="star4">⭐⭐⭐⭐ Good</label>

                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3">⭐⭐⭐ Average</label>

                        <input type="radio" id="star2" name="rating" value="2">
                        <label for="star2">⭐⭐ Poor</label>

                        <input type="radio" id="star1" name="rating" value="1">
                        <label for="star1">⭐ Very Poor</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Your Review (optional)</label>
                    <textarea name="review" rows="5" placeholder="Share your experience..."></textarea>
                </div>

                <div class="button-row">
                    <button type="submit" class="auth-button">Submit Review</button>
                    <a href="find.php" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

</body>
</html>