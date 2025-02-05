<?php
session_start();
$objectionId = isset($_GET['objection_id']) ? intval($_GET['objection_id']) : null;

if (!$objectionId) {
    echo "Objection ID is missing or invalid.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objection Details</title>
    <link rel="stylesheet" href="../../css/objection/objectionDetails.css">
</head>
<body>

<div class="menu">
    <a href="../../dashboard/admin_dashboard.php">View Dashboard</a>
    <a href="../application/viewApplications.html">View Applications</a>
    <a href="viewObjections.html">View Objections</a>
    <a href="../account/viewAccounts.html">View Accounts</a>
    <a href="../../../backend/authentication/logout.php">Log Out</a>
</div>

<div class="content">
    <h1>Objection Details</h1>

    <!-- Status Change Section -->
    <div class="details-section" id="statusChangeSection">
        <h2>Change Objection Status</h2>
        <select id="statusSelect">
            <option value="1">Submitted and Created</option>
            <option value="2">In Progress</option>
            <option value="3">Withdrawn</option>
            <option value="4">On Hold</option>
            <option value="5">Denied</option>
            <option value="6">Approved</option>
        </select>
        <button id="changeStatusBtn">Change Status</button>
    </div>

    <!-- Assign to me button -->
    <div id="assign-button-container"></div>

    <!-- Unassign to me button -->
    <div id="unassign-button-container"></div>

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
        const objectionId = <?php echo json_encode($objectionId); ?>;
        const sessionAdminId = <?php echo json_encode($_SESSION['admin_id']); ?>;
        let currentAdminId = null;

        if (!objectionId) {
            document.querySelector('#accountInfoList').innerHTML = '<li>objection ID is missing.</li>';
            return;
        }


        function fetchDetails() {
            fetch(`../../../backend/objection/getObjectionDetailsById.php?objection_id=${objectionId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
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
                        "Application ID": `<a href="../application/applicationDetails.php?application_id=${data.application_id}">${data.application_id}</a>`,
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


                    // Populate Supporting Documents
                    const supportingDocuments = {
                        "Document": data.supporting_documents.map(doc => doc.document)
                    };
                    for (const [key, value] of Object.entries(supportingDocuments)) {
                        const li = document.createElement('li');
                        li.innerHTML = `<strong>${key}:</strong> ${value === null || value === "null" ? 'Not available' : value}`;
                        documentList.appendChild(li);
                    }

                    // Fetch and Populate Comments
                    fetch(`../../../backend/objection/getAllCommentsByObjectionId.php?objection_id=${objectionId}`)
                        .then(response => response.json())
                        .then(commentsData => {
                            if (commentsData.error) {
                                commentList.innerHTML = `<li>${commentsData.error}</li>`;
                                return;
                            }

                            if (commentsData.length > 0) {
                                commentsData.forEach(comment => {
                                    const li = document.createElement('li');
                                    const userType = (comment.account_id == 1 || comment.account_id == 2) ? 'Admin' : 'User';
                                    li.innerHTML = `<strong>${userType} ID:</strong> ${comment.account_id} (${comment.timestamp}) → ${comment.comment}`;
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
                    fetch(`../../../backend/objection/getAllLogsByObjectionId.php?objection_id=${objectionId}`)
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

                    const assignBtnContainer = document.getElementById('assign-button-container');
                    const assignBtn = document.createElement('a');
                    assignBtn.href = `../../../backend/objection/admin/assignAdminToObjection.php?objection_id=${objectionId}`;
                    assignBtn.id = 'assign-btn';
                    assignBtn.className = 'assign-btn';
                    assignBtn.innerText = 'Assign to me';


                    const unassignBtnContainer = document.getElementById('unassign-button-container');
                    const unassignBtn = document.createElement('a');
                    unassignBtn.href = `../../../backend/objection/admin/unassignAdminFromObjection.php?objection_id=${objectionId}`;
                    unassignBtn.id = 'unassign-btn';
                    unassignBtn.className = 'unassign-btn';
                    unassignBtn.innerText = 'Unassign from me';

                    currentAdminId = data.admin_id;  // Retrieve the current admin ID

                    if(currentAdminId == null){
                        assignBtnContainer.appendChild(assignBtn);
                    }

                    if(currentAdminId != null && currentAdminId == sessionAdminId){
                        unassignBtnContainer.appendChild(unassignBtn);
                    }

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

            fetch('../../../backend/objection/postCommentByObjectionId.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json'
                },
                body: new URLSearchParams({
                    objection_id: objectionId,
                    comment: commentText
                })
            }).then(() => {
                document.getElementById('newComment').value = '';
                fetchDetails();
            });
        });


        // Toggle comments section
        const toggleCommentBtn = document.getElementById('toggleCommentBtn');
        const commentSection = document.getElementById('commentSection');
        toggleCommentBtn.addEventListener('click', function() {
            if (commentSection.classList.contains('hidden')) {
                commentSection.classList.remove('hidden');
                toggleCommentBtn.querySelector('.arrow').classList.add('arrow-up');
            } else {
                commentSection.classList.add('hidden');
                toggleCommentBtn.querySelector('.arrow').classList.remove('arrow-up');
            }
        });

        // Toggle logs section
        const toggleLogBtn = document.getElementById('toggleLogBtn');
        const logSection = document.getElementById('logSection');
        toggleLogBtn.addEventListener('click', function() {
            if (logSection.classList.contains('hidden')) {
                logSection.classList.remove('hidden');
                toggleLogBtn.querySelector('.arrow').classList.add('arrow-up');
            } else {
                logSection.classList.add('hidden');
                toggleLogBtn.querySelector('.arrow').classList.remove('arrow-up');
            }
        });

        document.getElementById('changeStatusBtn').addEventListener('click', function() {

            const selectedStatusId = document.getElementById('statusSelect').value;
            fetch(`../../../backend/objection/admin/setObjectionStatus.php?objection_id=${objectionId}&status_id=${selectedStatusId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Fetch successful:', data);
                    // Refresh the page after the fetch request completes
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });

    });
</script>

</body>
</html>
