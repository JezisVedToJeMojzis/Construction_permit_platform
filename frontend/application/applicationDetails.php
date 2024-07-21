<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['account_id'])) {
    header('Location: ../authentication/login.php'); // Redirect to login page if not logged in
    exit();
}

$account_id = $_SESSION['account_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
    <link rel="stylesheet" href="../css/application/applicationDetails.css">
</head>
<body>

<div class="menu">
    <a href="../dashboard/user_dashboard.php">View Dashboard</a>
    <a href="../profile/viewProfile.php">View Profile</a>
    <a href="submitApplication.html">Submit Application</a>
    <a href="viewApplications.html">View Applications</a>
    <a href="../objection/viewObjections.html">View Objections</a>
    <a href="../../backend/authentication/logout.php">Log Out</a>
</div>

<div class="content">
    <h1>Application Details</h1>

    <!-- Raise Objection button -->
    <a href="#" id="raise-objection-btn" class="raise-objection-btn">Raise Objection</a>

    <div id="withdraw-button-container">
        <!-- Withdraw button will be inserted here if user is the owner -->
    </div>

    <!-- General Information -->
    <div class="details-section">
        <h2>General Information</h2>
        <ul id="generalInfoList">
            <li>Loading...</li>
        </ul>
    </div>

    <!-- Property Details -->
    <div class="details-section">
        <h2>Property Details</h2>
        <ul id="propertyList">
            <li>Loading...</li>
        </ul>
    </div>

    <!-- Project Details -->
    <div class="details-section">
        <h2>Project Details</h2>
        <ul id="projectList">
            <li>Loading...</li>
        </ul>
    </div>

    <!-- Document Details -->
    <div class="details-section">
        <h2>Document Details</h2>
        <ul id="documentList">
            <li>Loading...</li>
        </ul>
    </div>

    <!-- Log Details -->
    <div class="details-section">
        <button class="toggle-btn" id="toggleLogBtn">
            <span class="arrow"></span> Logs
        </button>
        <div id="logSection" class="hidden">
            <ul id="logList">
                <li>Loading...</li>
            </ul>
        </div>
    </div>

    <!-- Comment Details -->
    <div class="details-section">
        <button class="toggle-btn" id="toggleCommentBtn">
            <span class="arrow"></span> Comments
        </button>
        <div id="commentSection" class="hidden">
            <ul id="commentList">
                <li>Loading...</li>
            </ul>
            <div class="comment-form">
                <textarea id="newComment" placeholder="Add a new comment..." rows="4" cols="50"></textarea>
                <button id="submitCommentBtn">Submit Comment</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const applicationId = urlParams.get('id');

        if (!applicationId) {
            document.querySelector('#generalInfoList').innerHTML = `<li>Application ID is missing.</li>`;
            return;
        }

        // Update the Raise Objection button href
        const raiseObjectionBtn = document.getElementById('raise-objection-btn');
        raiseObjectionBtn.href = `../objection/submitObjection.html?application_id=${applicationId}`;


        function fetchDetails() {
            fetch(`../../backend/application/getApplicationDetailsById.php?application_id=${applicationId}`)
                .then(response => response.json())
                .then(data => {
                    const generalInfoList = document.querySelector('#generalInfoList');
                    const propertyList = document.querySelector('#propertyList');
                    const projectList = document.querySelector('#projectList');
                    const documentList = document.querySelector('#documentList');
                    const commentList = document.querySelector('#commentList');
                    const logList = document.querySelector('#logList');

                    // Clear previous content
                    generalInfoList.innerHTML = '';
                    propertyList.innerHTML = '';
                    projectList.innerHTML = '';
                    documentList.innerHTML = '';
                    commentList.innerHTML = '';
                    logList.innerHTML = '';

                    if (data.error) {
                        generalInfoList.innerHTML = `<li>${data.error}</li>`;
                        return;
                    }

                    // Populate General Information
                    const generalInfo = {
                        "Application ID": data.application_id,
                        "Account ID": data.account_id,
                        "Admin ID": data.admin_id,
                        "Role": data.role,
                        "Status": data.application_status,
                        "Submission Date & Time": data.submission_date_and_time,
                        "Last Change": data.last_change
                    };

                    for (const [key, value] of Object.entries(generalInfo)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value === null || value === "null" ? 'Not available' : value}`;
                        generalInfoList.appendChild(li);
                    }

                    const withdrawBtnContainer = document.getElementById('withdraw-button-container');
                    const withdrawBtn = document.createElement('a');
                    withdrawBtn.href = `../../backend/application/withdrawApplication.php?application_id=${applicationId}`;
                    withdrawBtn.id = 'withdraw-btn';
                    withdrawBtn.className = 'withdraw-btn';
                    withdrawBtn.innerText = 'Withdraw Application';

                    // Add Withdraw Button if the logged-in user is the owner
                    if (data.account_id == <?php echo $account_id; ?>) {
                        withdrawBtnContainer.appendChild(withdrawBtn);
                    }

                    // Hide buttons based on status
                    const status = data.application_status;
                    if (status == "Withdrawn" || status == "Under Objection" || status == "Denied" || status == "Approved") {
                        withdrawBtnContainer.style.display = 'none';
                    } else {
                        withdrawBtnContainer.style.display = 'block';
                    }

                    // Hide the Raise Objection button
                    const raiseObjectionBtn = document.getElementById('raise-objection-btn');
                    if (status == "Withdrawn" || status == "Under Objection" || status == "Denied" || status == "Approved") {
                        raiseObjectionBtn.style.display = 'none';
                    } else {
                        raiseObjectionBtn.style.display = 'block';
                    }

                    // Populate Property Details
                    const properties = {
                        "Street": data.street,
                        "House Number": data.house_number,
                        "City": data.city,
                        "Post Code": data.post_code,
                        "Country": data.country,
                        "Parcel Number": data.parcel_number,
                        "Zoning District": data.zoning_district,
                        "Current Use": data.current_use,
                        "Proposed Use": data.proposed_use
                    };
                    for (const [key, value] of Object.entries(properties)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value === null || value === "null" ? 'Not available' : value}`;
                        propertyList.appendChild(li);
                    }

                    // Populate Project Details
                    const projects = {
                        "Title": data.project_title,
                        "Description": data.project_description,
                        "Type": data.project_type,
                        "Estimated Time": data.estimated_time,
                        "Estimated Cost": data.estimated_cost
                    };
                    for (const [key, value] of Object.entries(projects)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value === null || value === "null" ? 'Not available' : value}`;
                        projectList.appendChild(li);
                    }

                    // Populate Document Details
                    const documents = {
                        "Territorial Decision": data.territorial_decision,
                        "Ownership Document/Contractor Agreement": data.ownership_document_or_contractor_agreement,
                        "Project Documentation": data.project_documentation,
                        "Architectural Plan": data.architectural_plan,
                        "Designer Certificate": data.designer_certificate,
                        "Electricity Statement": data.electricity_statement,
                        "Water Statement": data.water_statement,
                        "Gas Statement": data.gas_statement,
                        "Telecommunications Statement": data.telecommunications_statement,
                        "Road Statement": data.road_statement,
                        "Traffic Statement": data.traffic_statement,
                        "Environmental Statement": data.environmental_statement
                    };
                    for (const [key, value] of Object.entries(documents)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value === null || value === "null" ? 'Not available' : value}`;
                        documentList.appendChild(li);
                    }

                    // Fetch and Populate Comments
                    fetch(`../../backend/application/getAllCommentsByApplicationId.php?application_id=${applicationId}`)
                        .then(response => response.json())
                        .then(commentsData => {
                            if (commentsData.error) {
                                commentList.innerHTML = `<li>${commentsData.error}</li>`;
                                return;
                            }

                            if (commentsData.length > 0) {
                                commentsData.forEach(comment => {
                                    const li = document.createElement('li');
                                    li.innerHTML = `<strong>Account ID:</strong> ${comment.account_id} (${comment.timestamp}) → ${comment.Comment}`;
                                    commentList.appendChild(li);
                                });
                            } else {
                                commentList.innerHTML = `<li>No comments available.</li>`;
                            }
                        })
                        .catch(error => {
                            commentList.innerHTML = `<li>Error loading comments.</li>`;
                            console.error('Error:', error);
                        });

                    // Fetch and Populate Logs
                    fetch(`../../backend/application/getAllLogsByApplicationId.php?application_id=${applicationId}`)
                        .then(response => response.json())
                        .then(logsData => {
                            if (logsData.error) {
                                logList.innerHTML = `<li>${logsData.error}</li>`;
                                return;
                            }

                            if (logsData.length > 0) {
                                logsData.forEach(log => {
                                    const li = document.createElement('li');
                                    li.innerHTML = `<strong>${log.timestamp}:</strong> ${log.description}`;
                                    logList.appendChild(li);
                                });
                            } else {
                                logList.innerHTML = `<li>No logs available.</li>`;
                            }
                        })
                        .catch(error => {
                            logList.innerHTML = `<li>Error loading logs.</li>`;
                            console.error('Error:', error);
                        });
                })
                .catch(error => {
                    document.querySelector('#generalInfoList').innerHTML = `<li>Error loading data.</li>`;
                    console.error('Error:', error);
                });
        }

        fetchDetails();

        document.getElementById('submitCommentBtn').addEventListener('click', function() {
            const commentText = document.getElementById('newComment').value.trim();
            if (commentText === '') {
                alert('Please enter a comment.');
                return;
            }

            fetch('../../backend/application/postCommentByApplicationId.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    application_id: applicationId,
                    comment: commentText
                })
            }).then(() => {
                document.getElementById('newComment').value = '';
                fetchDetails();
            });
        });

        // Toggle Log Details
        document.getElementById('toggleLogBtn').addEventListener('click', function() {
            const logSection = document.getElementById('logSection');
            const isHidden = logSection.classList.contains('hidden');
            logSection.classList.toggle('hidden');
            this.querySelector('.arrow').classList.toggle('arrow-up', !isHidden);
        });

        // Toggle Comment Details
        document.getElementById('toggleCommentBtn').addEventListener('click', function() {
            const commentSection = document.getElementById('commentSection');
            const isHidden = commentSection.classList.contains('hidden');
            commentSection.classList.toggle('hidden');
            this.querySelector('.arrow').classList.toggle('arrow-up', !isHidden);
        });
    });

</script>

</body>
</html>
