<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
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
    <table id="openApplicationsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Assigned Admin ID</th>
            <th>Role</th>
            <th>Submission Date</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="5">Loading...</td>
        </tr>
        </tbody>
    </table>

    <!-- Closed Applications Section -->
    <h3>Closed Applications</h3>
    <table id="closedApplicationsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Assigned Admin ID</th>
            <th>Role</th>
            <th>Submission Date</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="5">Loading...</td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountId = <?php echo json_encode($_SESSION["account_id"]); ?>;

        // Function to fetch and display applications
        function fetchApplications(url, tableId) {
            fetch(url + '?account_id=' + accountId)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector(`#${tableId} tbody`);
                    tableBody.innerHTML = ''; // Clear the loading row

                    if (data.error) {
                        tableBody.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="5">No applications found.</td></tr>`;
                    } else {
                        data.forEach(app => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${app.id}</td>
                                <td>${app.admin_id}</td>
                                <td>${app.role}</td>
                                <td>${app.submission_date_and_time}</td>
                                <td>${app.status_status}</td>
                            `;
                            row.addEventListener('click', () => {
                                window.location.href = `../application/applicationDetails.html?id=${app.id}`;
                            });
                            tableBody.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    document.querySelector(`#${tableId} tbody`).innerHTML = `<tr><td colspan="5">Error loading data.</td></tr>`;
                    console.error('Error:', error);
                });
        }

        // Fetch open and closed applications
        fetchApplications('../../backend/application/getAllOpenApplicationsByAccountId.php', 'openApplicationsTable');
        fetchApplications('../../backend/application/getAllClosedApplicationsByAccountId.php', 'closedApplicationsTable');
    });
</script>

</body>
</html>
