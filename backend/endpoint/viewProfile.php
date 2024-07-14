<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['account_id'])) {
    header("Location: ../login.php");
    exit();
}

function sanitize_output($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$isPrivate = ($_SESSION['account_role'] === 'private');
$isOrganization = ($_SESSION['account_role'] === 'organization');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/styles.css">
    <title>Profile</title>
</head>
<body>
<div class="menu">
    <a href="../dashboard/user_dashboard.php">View Dashboard</a>
    <a href="../endpoint/viewProfile.php">View Profile</a>
    <a href="submitApplication.php">Submit Application</a>
    <a href="viewApplications.php">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../authentication/logout.php">Log Out</a>
</div>

<div class="content">
    <h2>Profile</h2>
    <div class="profile-field">
        <label>Email:</label>
        <span><?php echo sanitize_output($_SESSION['account_email']); ?></span>
    </div>
    <div class="profile-field">
        <label>Phone Number:</label>
        <span><?php echo sanitize_output($_SESSION['phone_number']); ?></span>
    </div>
    <div class="profile-field">
        <label>Street:</label>
        <span><?php echo sanitize_output($_SESSION['street']); ?></span>
    </div>
    <div class="profile-field">
        <label>House Number:</label>
        <span><?php echo sanitize_output($_SESSION['house_number']); ?></span>
    </div>
    <div class="profile-field">
        <label>City:</label>
        <span><?php echo sanitize_output($_SESSION['city']); ?></span>
    </div>
    <div class="profile-field">
        <label>Post Code:</label>
        <span><?php echo sanitize_output($_SESSION['post_code']); ?></span>
    </div>
    <div class="profile-field">
        <label>Country:</label>
        <span><?php echo sanitize_output($_SESSION['country']); ?></span>
    </div>
    <?php if ($isPrivate): ?>
        <div class="profile-field">
            <label>First Name:</label>
            <span><?php echo sanitize_output($_SESSION['first_name']); ?></span>
        </div>
        <div class="profile-field">
            <label>Last Name:</label>
            <span><?php echo sanitize_output($_SESSION['last_name']); ?></span>
        </div>
        <div class="profile-field">
            <label>Identification Number:</label>
            <span><?php echo sanitize_output($_SESSION['identification_number']); ?></span>
        </div>
    <?php elseif ($isOrganization): ?>
        <div class="profile-field">
            <label>Organization Name:</label>
            <span><?php echo sanitize_output($_SESSION['organization_name']); ?></span>
        </div>
        <div class="profile-field">
            <label>Contact Person First Name:</label>
            <span><?php echo sanitize_output($_SESSION['contact_first_name']); ?></span>
        </div>
        <div class="profile-field">
            <label>Contact Person Last Name:</label>
            <span><?php echo sanitize_output($_SESSION['contact_last_name']); ?></span>
        </div>
        <div class="profile-field">
            <label>Registration Number:</label>
            <span><?php echo sanitize_output($_SESSION['registration_number']); ?></span>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
