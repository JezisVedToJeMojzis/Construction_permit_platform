<?php
session_start();

$account_id = $_SESSION['account_id'];
$account_email = $_SESSION['account_email'];
$account_role = $_SESSION['account_role'];
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
    <a href="user_dashboard.php">View Dashboard</a>
    <a href="../endpoint/viewProfile.php">View Profile</a>
    <a href="submitApplication.php">Submit Application</a>
    <a href="viewApplications.php">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../authentication/logout.php">Log Out</a>
</div>
<div class="content">
    <h2>User Dashboard</h2>
    <div class="profile-field">
        <label>Account ID:</label>
        <span><?php echo htmlspecialchars($account_id); ?></span>
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
