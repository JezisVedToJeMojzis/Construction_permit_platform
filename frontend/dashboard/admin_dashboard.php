<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Admin Dashboard</title>
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

    <!-- Profile Information -->
    <h3>Profile Information</h3>
    <ul class="profile-info">
        <li><strong>Account ID:</strong> <?php echo htmlspecialchars($_SESSION['account_id']); ?></li>
        <li><strong>First Name:</strong> <?php echo htmlspecialchars($_SESSION['first_name']); ?></li>
        <li><strong>Last Name:</strong> <?php echo htmlspecialchars($_SESSION['last_name']); ?></li>
        <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['account_email']); ?></li>
        <li><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['account_role']); ?></li>
    </ul>

    <!-- Assigned Applications -->
    <h3>Assigned Applications</h3>
    <ul class="assigned-applications">
        <!-- Sample data for demonstration -->
        <!-- Replace with actual data fetched from your endpoint -->
        <li>Application ID: 12345</li>
        <li>Application ID: 67890</li>
        <!-- If no assigned applications -->
        <!-- <li>No assigned applications.</li> -->
    </ul>

    <!-- Finished Applications -->
    <h3>Finished Applications</h3>
    <ul class="finished-applications">
        <!-- Sample data for demonstration -->
        <!-- Replace with actual data fetched from your endpoint -->
        <li>Application ID: 54321</li>
        <li>Application ID: 98765</li>
        <!-- If no finished applications -->
        <!-- <li>No finished applications.</li> -->
    </ul>
</div>
</body>
</html>
