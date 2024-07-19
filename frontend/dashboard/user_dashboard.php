<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>User Dashboard</title>
</head>
<body>
<div class="menu">
    <a href="user_dashboard.php">View Dashboard</a>
    <a href="../profile/viewProfile.php">View Profile</a>
    <a href="../application/submitApplication.html">Submit Application</a>
    <a href="../application/viewApplications.html">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../../backend/authentication/logout.php">Log Out</a>
</div>
<div class="content">
    <h2>User Dashboard</h2>

    <!-- Profile Information Section -->
    <h3>Profile Information</h3>
    <ul class="profile-info">
        <li><strong>Account ID:</strong> <?php echo htmlspecialchars($_SESSION["account_id"]); ?></li>
        <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION["account_email"]); ?></li>
        <li><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION["account_role"]); ?></li>
    </ul>

    <!-- Open Applications Section -->
    <h3>Open Applications</h3>
    <ul id="openApplications">
        <li>Loading...</li>
    </ul>

    <!-- Closed Applications Section -->
    <h3>Closed Applications</h3>
    <ul id="closedApplications">
        <li>Loading...</li>
    </ul>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountId = <?php echo json_encode($_SESSION["account_id"]); ?>;

        // Function to fetch and display applications
        function fetchApplications(url, listId) {
            fetch(url + '?account_id=' + accountId)
                .then(response => response.json())
                .then(data => {
                    const list = document.querySelector(`#${listId}`);
                    list.innerHTML = ''; // Clear the loading item

                    if (data.error) {
                        list.innerHTML = `<li>${data.error}</li>`;
                        return;
                    }

                    if (data.length === 0) {
                        list.innerHTML = `<li>No applications found.</li>`;
                    } else {
                        data.forEach(app => {
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                                <strong>ID:</strong> ${app.id} <br>
                                <strong>Status:</strong> ${app.status_status} <br>
                                <strong>Role:</strong> ${app.role} <br>
                                <strong>Submission Date:</strong> ${app.submission_date_and_time}
                            `;
                            list.appendChild(listItem);
                        });
                    }
                })
                .catch(error => {
                    document.querySelector(`#${listId}`).innerHTML = `<li>Error loading data.</li>`;
                    console.error('Error:', error);
                });
        }

        // Fetch open and closed applications
        fetchApplications('../../backend/application/getOpenApplications.php', 'openApplications');
        fetchApplications('../../backend/application/getClosedApplications.php', 'closedApplications');
    });
</script>

</body>
</html>
