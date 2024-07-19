<?php
session_start();
$isPrivate = ($_SESSION['account_role'] === 'private');
$isOrganization = ($_SESSION['account_role'] === 'organization');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <title>Profile</title>
</head>
<body>
<div class="menu">
    <a href="../dashboard/user_dashboard.php">View Dashboard</a>
    <a href="viewProfile.php">View Profile</a>
    <a href="../application/submitApplication.html">Submit Application</a>
    <a href="../application/viewApplications.html">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../../backend/authentication/logout.php">Log Out</a>
</div>

<div class="content">
    <h2>Profile</h2>
    <ul class="profile-info">
        <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['account_email']); ?></li>
        <li><strong>Phone Number:</strong> <?php echo htmlspecialchars($_SESSION['phone_number']); ?></li>
        <li><strong>Street:</strong> <?php echo htmlspecialchars($_SESSION['street']); ?></li>
        <li><strong>House Number:</strong> <?php echo htmlspecialchars($_SESSION['house_number']); ?></li>
        <li><strong>City:</strong> <?php echo htmlspecialchars($_SESSION['city']); ?></li>
        <li><strong>Post Code:</strong> <?php echo htmlspecialchars($_SESSION['post_code']); ?></li>
        <li><strong>Country:</strong> <?php echo htmlspecialchars($_SESSION['country']); ?></li>
        <?php if ($isPrivate): ?>
            <li><strong>First Name:</strong> <?php echo htmlspecialchars($_SESSION['first_name']); ?></li>
            <li><strong>Last Name:</strong> <?php echo htmlspecialchars($_SESSION['last_name']); ?></li>
            <li><strong>Identification Number:</strong> <?php echo htmlspecialchars($_SESSION['identification_number']); ?></li>
        <?php elseif ($isOrganization): ?>
            <li><strong>Organization Name:</strong> <?php echo htmlspecialchars($_SESSION['organization_name']); ?></li>
            <li><strong>Contact Person First Name:</strong> <?php echo htmlspecialchars($_SESSION['contact_first_name']); ?></li>
            <li><strong>Contact Person Last Name:</strong> <?php echo htmlspecialchars($_SESSION['contact_last_name']); ?></li>
            <li><strong>Registration Number:</strong> <?php echo htmlspecialchars($_SESSION['registration_number']); ?></li>
        <?php endif; ?>
    </ul>
</div>
</body>
</html>
