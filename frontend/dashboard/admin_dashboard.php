<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>User Dashboard</title>
</head>
<body>
<div class="menu">
    <a href="admin_dashboard.php">View Dashboard</a>
    <a href="../application/viewApplications.html">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../../backend/authentication/logout.php">Log Out</a>
</div>
<div class="content">
    <h2>Admin Dashboard</h2>
    <div class="profile-field">
        <label>Account ID:</label>
        <span><?php echo htmlspecialchars($_SESSION['account_id']); ?></span>
    </div>
    <div class="profile-field">
        <label>First Name:</label>
        <span><?php echo htmlspecialchars($_SESSION['first_name']); ?></span>
    </div>
    <div class="profile-field">
        <label>Last Name:</label>
        <span><?php echo htmlspecialchars($_SESSION['last_name']); ?></span>
    </div>
    <div class="profile-field">
        <label>Email:</label>
        <span><?php echo htmlspecialchars($_SESSION['account_email']); ?></span>
    </div>
    <div class="profile-field">
        <label>Role:</label>
        <span><?php echo htmlspecialchars($_SESSION['account_role']); ?></span>
    </div>
</div>
</body>
</html>
