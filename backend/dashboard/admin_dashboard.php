<?php
session_start();

$account_id = $_SESSION['account_id'];
$account_email = $_SESSION['account_email'];
$account_role = $_SESSION['account_role'];
$account_first_name = $_SESSION['first_name'];
$account_last_name = $_SESSION['last_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/styles.css">
    <title>User Dashboard</title>
</head>
<body>
<div class="menu">
    <a href="admin_dashboard.php">View Dashboard</a>
    <a href="viewApplications.php">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../authentication/logout.php">Log Out</a>
</div>
<div class="content">
    <h2>Admin Dashboard</h2>
    <div class="profile-field">
        <label>Account ID:</label>
        <span><?php echo htmlspecialchars($account_id); ?></span>
    </div>
    <div class="profile-field">
        <label>First Name:</label>
        <span><?php echo htmlspecialchars($account_first_name); ?></span>
    </div>
    <div class="profile-field">
        <label>Last Name:</label>
        <span><?php echo htmlspecialchars($account_last_name); ?></span>
    </div>
    <div class="profile-field">
        <label>Email:</label>
        <span><?php echo htmlspecialchars($account_email); ?></span>
    </div>
    <div class="profile-field">
        <label>Role:</label>
        <span><?php echo htmlspecialchars($account_role); ?></span>
    </div>
</div>
</body>
</html>
