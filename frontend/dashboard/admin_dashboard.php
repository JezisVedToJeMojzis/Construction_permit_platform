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
        <li><strong>Admin ID:</strong> <?php echo htmlspecialchars($_SESSION['admin_id']); ?></li>
        <li><strong>First Name:</strong> <?php echo htmlspecialchars($_SESSION['first_name']); ?></li>
        <li><strong>Last Name:</strong> <?php echo htmlspecialchars($_SESSION['last_name']); ?></li>
        <li><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['account_email']); ?></li>
        <li><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['account_role']); ?></li>
    </ul>

    <br>

    <h3>Applications List</h3>

    <div class="controls">
        <label><strong>Application Status:</strong></label>
        <label><input type="radio" name="statusFilter1" value="open" checked> Open Applications</label>
        <label><input type="radio" name="statusFilter1" value="closed"> Closed Applications</label>
    </div>

    <br>

    <table id="applicationsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Account ID</th>
            <th>Admin ID</th>
            <th>Role</th>
            <th>Status Description</th>
            <th>Submission Date and Time</th>
            <th>Last Change</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="6">Loading...</td>
        </tr>
        </tbody>
    </table>

    <br>

    <h3>Objections List</h3>

    <div class="controls">
        <label><strong>Objection Status:</strong></label>
        <label><input type="radio" name="statusFilter" value="open" checked> Open Objections</label>
        <label><input type="radio" name="statusFilter" value="closed"> Closed Objections</label>
    </div>

    <br>

    <table id="objectionsTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Account ID</th>
            <th>Application ID</th>
            <th>Admin ID</th>
            <th>Brief Summary</th>
            <th>Detailed Explanation</th>
            <th>Affected Parties</th>
            <th>Status</th>
            <th>Submission Date and Time</th>
            <th>Last Change</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="10">Loading...</td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    function fetchApplications(status) {
        const endpoint = status === 'closed'
            ? '../../backend/application/admin/getAllClosedAssignedApplicationsByAdminId.php'
            : '../../backend/application/admin/getAllOpenAssignedApplicationsByAdminId.php';

        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                const tableBody = document.querySelector('#applicationsTable tbody');
                tableBody.innerHTML = '';  // Clear the loading row

                if (data.error) {
                    tableBody.innerHTML = `<tr><td colspan="7">${data.error}</td></tr>`;
                    return;
                }

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="7">No applications found.</td></tr>`;
                } else {
                    let filteredData = data;

                    filteredData.forEach(app => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${app.id}</td>
                            <td>${app.account_id}</td>
                            <td>${app.admin_id}</td>
                            <td>${app.role}</td>
                            <td>${app.status_status}</td>
                            <td>${app.submission_date_and_time}</td>
                            <td>${app.last_change}</td>
                        `;
                        row.addEventListener('click', () => {
                            window.location.href = `../admin/application/applicationDetails.php?application_id=${app.id}`;
                        });
                        tableBody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                document.querySelector('#applicationsTable tbody').innerHTML = `<tr><td colspan="7">Error loading data.</td></tr>`;
                console.error('Error:', error);
            });
    }

    function fetchObjections(status) {
        const endpoint = status === 'closed'
            ? '../../backend/objection/admin/getAllClosedAssignedObjectionsByAdminId.php'
            : '../../backend/objection/admin/getAllOpenAssignedObjectionsByAdminId.php';

        fetch(endpoint)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#objectionsTable tbody');
                tableBody.innerHTML = '';  // Clear the loading row

                if (data.error) {
                    tableBody.innerHTML = `<tr><td colspan="10">${data.error}</td></tr>`;
                    return;
                }

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="10">No objections found.</td></tr>`;
                } else {
                    let filteredData = data;

                    filteredData.forEach(obj => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${obj.id}</td>
                            <td>${obj.account_id}</td>
                            <td>${obj.application_id}</td>
                            <td>${obj.admin_id}</td>
                            <td>${obj.brief_summary}</td>
                            <td>${obj.detailed_explanation}</td>
                            <td>${obj.affected_parties}</td>
                            <td>${obj.status_status}</td>
                            <td>${new Date(obj.submission_date_and_time).toLocaleString()}</td>
                            <td>${new Date(obj.last_change).toLocaleString()}</td>
                        `;
                        row.addEventListener('click', () => {
                            window.location.href = `../admin/objection/objectionDetails.php?objection_id=${obj.id}`;
                        });
                        tableBody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                document.querySelector('#objectionsTable tbody').innerHTML = `<tr><td colspan="10">Error loading data.</td></tr>`;
                console.error('Error:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchObjections('open');

        document.querySelectorAll('input[name="statusFilter"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedStatus = this.value;
                fetchObjections(selectedStatus);
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        fetchApplications('open');

        document.querySelectorAll('input[name="statusFilter1"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedStatus = this.value;
                fetchApplications(selectedStatus);
            });
        });
    });
</script>
</body>
</html>
