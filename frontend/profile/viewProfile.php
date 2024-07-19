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
    <link rel="stylesheet" href="../css/styles.css">
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
    <div class="profile-field">
        <label>Email:</label>
        <span><?php echo $_SESSION['account_email']; ?></span>
    </div>
    <div class="profile-field">
        <label>Phone Number:</label>
        <span><?php echo $_SESSION['phone_number']; ?></span>
    </div>
    <div class="profile-field">
        <label>Street:</label>
        <span><?php echo $_SESSION['street']; ?></span>
    </div>
    <div class="profile-field">
        <label>House Number:</label>
        <span><?php echo $_SESSION['house_number']; ?></span>
    </div>
    <div class="profile-field">
        <label>City:</label>
        <span><?php echo $_SESSION['city']; ?></span>
    </div>
    <div class="profile-field">
        <label>Post Code:</label>
        <span><?php echo $_SESSION['post_code']; ?></span>
    </div>
    <div class="profile-field">
        <label>Country:</label>
        <span><?php echo $_SESSION['country']; ?></span>
    </div>
    <?php if ($isPrivate): ?>
        <div class="profile-field">
            <label>First Name:</label>
            <span><?php echo $_SESSION['first_name']; ?></span>
        </div>
        <div class="profile-field">
            <label>Last Name:</label>
            <span><?php echo $_SESSION['last_name']; ?></span>
        </div>
        <div class="profile-field">
            <label>Identification Number:</label>
            <span><?php echo $_SESSION['identification_number']; ?></span>
        </div>
    <?php elseif ($isOrganization): ?>
        <div class="profile-field">
            <label>Organization Name:</label>
            <span><?php echo $_SESSION['organization_name']; ?></span>
        </div>
        <div class="profile-field">
            <label>Contact Person First Name:</label>
            <span><?php echo $_SESSION['contact_first_name']; ?></span>
        </div>
        <div class="profile-field">
            <label>Contact Person Last Name:</label>
            <span><?php echo $_SESSION['contact_last_name']; ?></span>
        </div>
        <div class="profile-field">
            <label>Registration Number:</label>
            <span><?php echo $_SESSION['registration_number']; ?></span>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
