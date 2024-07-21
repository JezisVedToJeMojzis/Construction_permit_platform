<?php
// Get account_id from URL
$requestedAccountId = isset($_GET['account_id']) ? intval($_GET['account_id']) : null;

// Ensure the account_id is provided and valid
if (!$requestedAccountId) {
    echo "Account ID is missing or invalid.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="../../css/account/accountDetails.css">
</head>
<body>

<div class="menu">
    <a href="../../dashboard/admin_dashboard.php">View Dashboard</a>
    <a href="../application/viewApplications.html">View Applications</a>
    <a href="../objection/viewObjections.html">View Objections</a>
    <a href="viewAccounts.html">View Accounts</a>
    <a href="../../../backend/authentication/logout.php">Log Out</a>
</div>

<div class="content">
    <h1>Account Details</h1>

    <!-- Account Information -->
    <div class="details-section">
        <h2>Account Information</h2>
        <ul id="accountInfoList">
            <li>Loading...</li>
        </ul>
    </div>

    <!-- Applications Filter -->
    <div class="details-section">
        <h2>Applications</h2>
        <form id="applicationsFilterForm">
            <input type="radio" id="openApplications" name="applicationStatus" value="open" checked>
            <label for="openApplications">Open Applications</label>
            <input type="radio" id="closedApplications" name="applicationStatus" value="closed">
            <label for="closedApplications">Closed Applications</label>
        </form>
        <table id="applicationsList">
            <thead>
            <tr>
                <th>Application ID</th>
                <th>Account ID</th>
                <th>Status ID</th>
                <th>Admin ID</th>
                <th>Role</th>
                <th>Submission Date and Time</th>
                <th>Last Change</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan="7">Loading...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Objections Filter -->
    <div class="details-section">
        <h2>Objections</h2>
        <form id="objectionsFilterForm">
            <input type="radio" id="openObjections" name="objectionStatus" value="open" checked>
            <label for="openObjections">Open Objections</label>
            <input type="radio" id="closedObjections" name="objectionStatus" value="closed">
            <label for="closedObjections">Closed Objections</label>
        </form>
        <table id="objectionsList">
            <thead>
            <tr>
                <th>Objection ID</th>
                <th>Account ID</th>
                <th>Application ID</th>
                <th>Status ID</th>
                <th>Admin ID</th>
                <th>Brief Summary</th>
                <th>Detailed Explanation</th>
                <th>Affected Parties</th>
                <th>Submission Date and Time</th>
                <th>Last Change</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan="10">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accountId = <?php echo json_encode($requestedAccountId); ?>;

        if (!accountId) {
            document.querySelector('#accountInfoList').innerHTML = '<li>Account ID is missing.</li>';
            return;
        }

        function fetchAccountDetails() {
            fetch(`../../../backend/account/getAccountDetailsByAccountId.php?account_id=${accountId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data received:', data);
                    const accountInfoList = document.querySelector('#accountInfoList');
                    accountInfoList.innerHTML = '';

                    if (data.error) {
                        accountInfoList.innerHTML = `<li>${data.error}</li>`;
                        return;
                    }

                    // Populate account information based on type
                    const accountInfo = {
                        "ID": data.account_id,
                        "Email": data.email,
                        "Role": data.role,
                    };

                    if (data.role === 'private') {
                        accountInfo["First Name"] = data.name_or_first_name;
                        accountInfo["Last Name"] = data.last_name_or_contact_last_name;
                        accountInfo["Phone Number"] = data.phone_number;
                        accountInfo["Street"] = data.street;
                        accountInfo["House Number"] = data.house_number;
                        accountInfo["City"] = data.city;
                        accountInfo["Post Code"] = data.post_code;
                        accountInfo["Country"] = data.country;
                        accountInfo["Identification Number"] = data.identification_or_registration_number;
                    } else if (data.role === 'organization') {
                        accountInfo["Name"] = data.name_or_first_name;
                        accountInfo["Contact First Name"] = data.name_or_first_name;
                        accountInfo["Contact Last Name"] = data.last_name_or_contact_last_name;
                        accountInfo["Phone Number"] = data.phone_number;
                        accountInfo["Street"] = data.street;
                        accountInfo["House Number"] = data.house_number;
                        accountInfo["City"] = data.city;
                        accountInfo["Post Code"] = data.post_code;
                        accountInfo["Country"] = data.country;
                        accountInfo["Registration Number"] = data.identification_or_registration_number;
                    }

                    for (const [key, value] of Object.entries(accountInfo)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value || 'Not available'}`;
                        accountInfoList.appendChild(li);
                    }
                })
                .catch(error => {
                    document.querySelector('#accountInfoList').innerHTML = '<li>Error loading data.</li>';
                    console.error('Error:', error);
                });
        }

        function fetchApplications(status) {
            let endpoint = '';
            if (status === 'open') {
                endpoint = `../../../backend/application/getAllOpenApplicationsByAccountId.php?account_id=${accountId}`;
            } else if (status === 'closed') {
                endpoint = `../../../backend/application/getAllClosedApplicationsByAccountId.php?account_id=${accountId}`;
            }

            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    console.log('Applications Data received:', data);
                    const applicationsList = document.querySelector('#applicationsList tbody');
                    applicationsList.innerHTML = '';

                    if (data.error) {
                        applicationsList.innerHTML = `<tr><td colspan="7">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length > 0) {
                        data.forEach(app => {
                            const appRow = document.createElement('tr');
                            appRow.className = 'clickable-row';
                            appRow.dataset.url = `../application/applicationDetails.php?application_id=${app.id}`;

                            appRow.innerHTML = `
                                <td>${app.id || 'Not available'}</td>
                                <td>${app.account_id || 'Not available'}</td>
                                <td>${app.status_status || 'Not available'}</td>
                                <td>${app.admin_id || 'Unassigned'}</td>
                                <td>${app.role || 'Not available'}</td>
                                <td>${app.submission_date_and_time || 'Not available'}</td>
                                <td>${app.last_change || 'Not available'}</td>
                            `;

                            applicationsList.appendChild(appRow);
                        });
                    } else {
                        applicationsList.innerHTML = '<tr><td colspan="7">No applications found.</td></tr>';
                    }
                })
                .catch(error => {
                    document.querySelector('#applicationsList tbody').innerHTML = '<tr><td colspan="7">Error loading applications.</td></tr>';
                    console.error('Error:', error);
                });
        }

        function fetchObjections(status) {
            let endpoint = '';
            if (status === 'open') {
                endpoint = `../../../backend/objection/getAllOpenObjectionsByAccountId.php?account_id=${accountId}`;
            } else if (status === 'closed') {
                endpoint = `../../../backend/objection/getAllClosedObjectionsByAccountId.php?account_id=${accountId}`;
            }

            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    console.log('Objections Data received:', data);
                    const objectionsList = document.querySelector('#objectionsList tbody');
                    objectionsList.innerHTML = '';

                    if (data.error) {
                        objectionsList.innerHTML = `<tr><td colspan="10">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length > 0) {
                        data.forEach(obj => {
                            const objRow = document.createElement('tr');
                            objRow.className = 'clickable-row';
                            objRow.dataset.url = `../objection/objectionDetails.php?objection_id=${obj.id}`;

                            objRow.innerHTML = `
                                <td>${obj.id || 'Not available'}</td>
                                <td>${obj.account_id || 'Not available'}</td>
                                <td>${obj.application_id || 'Not available'}</td>
                                <td>${obj.status_status || 'Not available'}</td>
                                <td>${obj.admin_id || 'Unassigned'}</td>
                                <td>${obj.brief_summary || 'Not available'}</td>
                                <td>${obj.detailed_explanation || 'Not available'}</td>
                                <td>${obj.affected_parties || 'Not available'}</td>
                                <td>${obj.submission_date_and_time || 'Not available'}</td>
                                <td>${obj.last_change || 'Not available'}</td>
                            `;

                            objectionsList.appendChild(objRow);
                        });
                    } else {
                        objectionsList.innerHTML = '<tr><td colspan="10">No objections found.</td></tr>';
                    }
                })
                .catch(error => {
                    document.querySelector('#objectionsList tbody').innerHTML = '<tr><td colspan="10">Error loading objections.</td></tr>';
                    console.error('Error:', error);
                });
        }

        // Initial data fetch
        fetchAccountDetails();
        fetchApplications('open');
        fetchObjections('open');

        document.querySelectorAll('input[name="applicationStatus"]').forEach(radio => {
            radio.addEventListener('change', () => {
                fetchApplications(radio.value);
            });
        });

        document.querySelectorAll('input[name="objectionStatus"]').forEach(radio => {
            radio.addEventListener('change', () => {
                fetchObjections(radio.value);
            });
        });

        // clickable rows
        document.querySelector('#applicationsList').addEventListener('click', function(event) {
            if (event.target.closest('.clickable-row')) {
                window.location.href = event.target.closest('.clickable-row').dataset.url;
            }
        });

        document.querySelector('#objectionsList').addEventListener('click', function(event) {
            if (event.target.closest('.clickable-row')) {
                window.location.href = event.target.closest('.clickable-row').dataset.url;
            }
        });
    });
</script>

</body>
</html>