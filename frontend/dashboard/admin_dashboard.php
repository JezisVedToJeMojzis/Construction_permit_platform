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
    <a href="../admin/application/viewApplications.html">View Applications</a>
    <a href="../admin/objection/viewObjections.html">View Objections</a>
    <a href="../admin/account/viewAccounts.html">View Accounts</a>
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
        <li>Application ID: 12345</li>
        <li>Application ID: 67890</li>
    </ul>

    <!-- Assigned Objections -->
    <h3>Assigned Objections</h3>
    <ul class="assigned-objections">
        <li>Objection ID: 54321</li>
        <li>Objection ID: 98765</li>
    </ul>
</div>
</body>
</html>
