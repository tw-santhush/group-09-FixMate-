<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixMate - Local Service Pro Directory</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <div class="header-container">
        <a href="home.php" class="logo">
            <span class="logo-icon">🔧</span>
            <span>FixMate</span>
        </a>
        
        <nav class="nav-tabs">
            <a href="home.php" class="nav-link">Home</a>
            <a href="find.php" class="nav-link">Find a Service Pro</a>
            <a href="dashboard.php" class="nav-link">Technician Dashboard</a>
            <a href="admin_panel.php" class="nav-link">Admin Panel</a>
        </nav>

<?php 
// Check what user is logged in
if (isset($_SESSION['user_id'])) {
    // Normal user logged in
    echo '<a href="user_profile.php" class="login-btn">' . htmlspecialchars($_SESSION['user_name']) . '</a>';
} else if (isset($_SESSION['tech_id'])) {
    // Technician logged in
    echo '<a href="dashboard.php" class="login-btn">' . htmlspecialchars($_SESSION['tech_name']) . '</a>';
} else if (isset($_SESSION['admin_id'])) {
    // Admin logged in
    echo '<a href="admin_panel.php" class="login-btn">Admin Panel</a>';
} else {
    // Not logged in
    echo '<a href="login.php" class="login-btn">Login</a>';
}
?>
    </div>
</header>