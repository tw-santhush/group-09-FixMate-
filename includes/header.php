<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixMate</title>
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
            <a href="home.php#about" class="nav-link">About</a>

            <?php if (isset($_SESSION['tech_id'])) { ?>
                <a href="dashboard.php" class="nav-link">Technician Dashboard</a>
            <?php } ?>

            <?php if (isset($_SESSION['admin_id'])) { ?>
                <a href="admin_panel.php" class="nav-link">Admin Panel</a>
            <?php } ?>
        </nav>

        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<a href="user_profile.php" class="login-btn">' . $_SESSION['user_name'] . '</a>';
        } elseif (isset($_SESSION['tech_id'])) {
            echo '<a href="dashboard.php" class="login-btn">' . $_SESSION['tech_name'] . '</a>';
        } elseif (isset($_SESSION['admin_id'])) {
            echo '<a href="admin_panel.php" class="login-btn">Admin Panel</a>';
        } else {
            echo '<a href="login.php" class="login-btn">Login</a>';
        }
        ?>
    </div>
</header>