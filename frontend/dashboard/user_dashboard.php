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
    <a href="../objection/viewObjections.html">View Objections</a>
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

    <!-- Applications Section -->
    <h3>Applications</h3>
    <div class="status-group">
        <input type="radio" id="open-applications" name="application-status" value="open" checked>
        <label for="open-applications">Open Applications</label>
        <input type="radio" id="closed-applications" name="application-status" value="closed">
        <label for="closed-applications">Closed Applications</label>
    </div>
    <br>

    <table id="applicationsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Assigned Admin ID</th>
            <th>Role</th>
            <th>Submission Date</th>
            <th>Last Change</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="6">Loading...</td>
        </tr>
        </tbody>
    </table>

    <!-- Objections Section -->
    <h3>Objections</h3>
    <div class="status-group">
        <input type="radio" id="open-objections" name="objection-status" value="open" checked>
        <label for="open-objections">Open Objections</label>
        <input type="radio" id="closed-objections" name="objection-status" value="closed">
        <label for="closed-objections">Closed Objections</label>
    </div>
    <br>

    <table id="objectionsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Application ID</th>
            <th>Assigned Admin ID</th>
            <th>Brief Summary</th>
            <th>Submission Date</th>
            <th>Last Change</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="7">Loading...</td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountId = <?php echo json_encode($_SESSION["account_id"]); ?>;
        const applicationsTable = document.querySelector('#applicationsTable tbody');
        const objectionsTable = document.querySelector('#objectionsTable tbody');
        const openApplicationsRadio = document.getElementById('open-applications');
        const closedApplicationsRadio = document.getElementById('closed-applications');
        const openObjectionsRadio = document.getElementById('open-objections');
        const closedObjectionsRadio = document.getElementById('closed-objections');

        // Function to fetch and display applications
        function fetchApplications(url) {
            fetch(url + '?account_id=' + accountId)
                .then(response => response.json())
                .then(data => {
                    applicationsTable.innerHTML = ''; // Clear the loading row

                    if (data.error) {
                        applicationsTable.innerHTML = `<tr><td colspan="6">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length === 0) {
                        applicationsTable.innerHTML = `<tr><td colspan="6">No applications found.</td></tr>`;
                    } else {
                        data.forEach(app => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${app.id}</td>
                                <td>${app.admin_id}</td>
                                <td>${app.role}</td>
                                <td>${app.submission_date_and_time}</td>
                                <td>${app.last_change}</td>
                                <td>${app.status_status}</td>
                            `;
                            row.addEventListener('click', () => {
                                window.location.href = `../application/applicationDetails.php?id=${app.id}`;
                            });
                            applicationsTable.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    applicationsTable.innerHTML = `<tr><td colspan="6">Error loading data.</td></tr>`;
                    console.error('Error:', error);
                });
        }

        // Function to fetch and display objections
        function fetchObjections(url) {
            fetch(url + '?account_id=' + accountId)
                .then(response => response.json())
                .then(data => {
                    objectionsTable.innerHTML = ''; // Clear the loading row

                    if (data.error) {
                        objectionsTable.innerHTML = `<tr><td colspan="7">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length === 0) {
                        objectionsTable.innerHTML = `<tr><td colspan="7">No objections found.</td></tr>`;
                    } else {
                        data.forEach(obj => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${obj.id}</td>
                                <td>${obj.application_id}</td>
                                <td>${obj.admin_id}</td>
                                <td>${obj.brief_summary}</td>
                                <td>${obj.submission_date_and_time}</td>
                                <td>${obj.last_change}</td>
                                <td>${obj.status_status}</td>
                            `;
                            row.addEventListener('click', () => {
                                window.location.href = `../objection/objectionDetails.php`;
                            });
                            objectionsTable.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    objectionsTable.innerHTML = `<tr><td colspan="7">Error loading data.</td></tr>`;
                    console.error('Error:', error);
                });
        }

        // Event listeners for applications radio buttons
        openApplicationsRadio.addEventListener('change', () => {
            if (openApplicationsRadio.checked) {
                fetchApplications('../../backend/application/getAllOpenApplicationsByAccountId.php');
            }
        });

        closedApplicationsRadio.addEventListener('change', () => {
            if (closedApplicationsRadio.checked) {
                fetchApplications('../../backend/application/getAllClosedApplicationsByAccountId.php');
            }
        });

        // Event listeners for objections radio buttons
        openObjectionsRadio.addEventListener('change', () => {
            if (openObjectionsRadio.checked) {
                fetchObjections('../../backend/objection/getAllOpenObjectionsByAccountId.php');
            }
        });

        closedObjectionsRadio.addEventListener('change', () => {
            if (closedObjectionsRadio.checked) {
                fetchObjections('../../backend/objection/getAllClosedObjectionsByAccountId.php');
            }
        });

        // Initial fetch for open applications and open objections
        fetchApplications('../../backend/application/getAllOpenApplicationsByAccountId.php');
        fetchObjections('../../backend/objection/getAllOpenObjectionsByAccountId.php');
    });
</script>

</body>
</html>
