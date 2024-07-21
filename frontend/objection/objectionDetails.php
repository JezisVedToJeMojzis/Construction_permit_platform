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
    <title>Objection Details</title>
    <link rel="stylesheet" href="../css/objection/objectionDetails.css">
</head>
<body>

<div class="menu">
    <a href="../dashboard/user_dashboard.php">View Dashboard</a>
    <a href="../profile/viewProfile.php">View Profile</a>
    <a href="submitApplication.html">Submit Application</a>
    <a href="viewApplications.html">View Applications</a>
    <a href="../objection/viewObjections.php">View Objections</a>
    <a href="../../backend/authentication/logout.php">Log Out</a>
</div>

<div class="content">
    <h1>Objection Details</h1>

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

    <!-- Objection Details -->
    <div class="details-section">
        <h2>Objection Details</h2>
        <ul id="objectionDetailsList">
            <li>Loading...</li>
        </ul>
    </div>

    <!-- Document Details -->
    <div class="details-section">
        <h2>Supporting Documents</h2>
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
        const objectionId = urlParams.get('id');

        if (!objectionId) {
            document.querySelector('#generalInfoList').innerHTML = `<li>Objection ID is missing.</li>`;
            return;
        }

        function fetchDetails() {
            fetch(`../../backend/objection/getObjectionDetailsById.php?objection_id=${objectionId}`)
                .then(response => response.json())
                .then(data => {
                    const generalInfoList = document.querySelector('#generalInfoList');
                    const objectionDetailsList = document.querySelector('#objectionDetailsList');
                    const documentList = document.querySelector('#documentList');
                    const commentList = document.querySelector('#commentList');
                    const logList = document.querySelector('#logList');

                    // Clear previous content
                    generalInfoList.innerHTML = '';
                    objectionDetailsList.innerHTML = '';
                    documentList.innerHTML = '';
                    commentList.innerHTML = '';
                    logList.innerHTML = '';

                    if (data.error) {
                        generalInfoList.innerHTML = `<li>${data.error}</li>`;
                        return;
                    }

                    // Populate General Information
                    const generalInfo = {
                        "Objection ID": data.objection_id,
                        "Application ID": data.application_id,
                        "Account ID": data.account_id,
                        "Admin ID": data.admin_id,
                        "Status": data.objection_status,
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
                    withdrawBtn.href = `../../backend/objection/withdrawObjection.php?objection_id=${objectionId}`;
                    withdrawBtn.id = 'withdraw-btn';
                    withdrawBtn.className = 'withdraw-btn';
                    withdrawBtn.innerText = 'Withdraw Objection';

                    // Add Withdraw Button if the logged-in user is the owner
                    if (data.account_id == <?php echo $account_id; ?>) {
                        withdrawBtnContainer.appendChild(withdrawBtn);
                    }

                    // Hide buttons based on status
                    const status = data.objection_status;
                    if (status == "Withdrawn" || status == "Resolved" || status == "Denied") {
                        withdrawBtnContainer.style.display = 'none';
                    } else {
                        withdrawBtnContainer.style.display = 'block';
                    }

                    // Populate Objection Details
                    const objectionDetails = {
                        "Brief Summary": data.brief_summary,
                        "Detailed Explanation": data.detailed_explanation,
                        "Affected Parties": data.affected_parties
                    };
                    for (const [key, value] of Object.entries(objectionDetails)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value === null || value === "null" ? 'Not available' : value}`;
                        objectionDetailsList.appendChild(li);
                    }

                    // Populate Document Details
                    if (data.supporting_documents.length > 0) {
                        data.supporting_documents.forEach(doc => {
                            const li = document.createElement('li');
                            li.innerHTML = `<strong>Document:</strong> ${doc.document}`;
                            documentList.appendChild(li);
                        });
                    } else {
                        documentList.innerHTML = '<li>No supporting documents available.</li>';
                    }

                    // Populate Log Details
                    if (data.logs.length > 0) {
                        data.logs.forEach(log => {
                            const li = document.createElement('li');
                            li.innerHTML = `<strong>Description:</strong> ${log.log_description}, <strong>Timestamp:</strong> ${log.log_timestamp}`;
                            logList.appendChild(li);
                        });
                    } else {
                        logList.innerHTML = '<li>No logs available.</li>';
                    }

                    // Populate Comment Details
                    if (data.comments.length > 0) {
                        data.comments.forEach(comment => {
                            const li = document.createElement('li');
                            li.innerHTML = `<strong>Account ID:</strong> ${comment.comment_account_id}, <strong>Comment:</strong> ${comment.comment}, <strong>Timestamp:</strong> ${comment.comment_timestamp}`;
                            commentList.appendChild(li);
                        });
                    } else {
                        commentList.innerHTML = '<li>No comments available.</li>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching objection details:', error);
                    document.querySelector('#generalInfoList').innerHTML = `<li>An error occurred while fetching objection details.</li>`;
                });
        }

        fetchDetails();
    });
</script>

</body>
</html>
