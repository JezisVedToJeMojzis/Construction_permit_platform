<?php
session_start();
require_once "../PDO/PDOApplicationManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOApplicationManager($serverName, $userName, $userPassword, $databaseName);

    $role = isset($_POST['selected_role']) ? $_POST['selected_role'] : '';
    $application_description = isset($_POST['application_description']) ? $_POST['application_description'] : '';
    $street = $_POST['street'];
    $houseNumber = $_POST['house_number'];
    $city = $_POST['city'];
    $postCode = $_POST['post_code'];
    $country = $_POST['country'];
    $parcelNumber = $_POST['parcel_number'];
    $zoningDistrict = $_POST['zoning_district'];
    $currentUse = $_POST['current_use'];
    $proposedUse = $_POST['proposed_use'];
    $projectTitle = $_POST['project_title'];
    $projectDescription = $_POST['project_description'];
    $projectType = isset($_POST['project_type']) ? $_POST['project_type'] : '';
    $estimatedTime = $_POST['estimated_time'];
    $estimatedCost = $_POST['estimated_cost'];
    $territorialDecision = $_POST['territorial_decision'];
    $ownershipOrContractor = $_POST['ownership_document_or_contractor_agreement'];
    $projectDocumentation = $_POST['project_documentation'];
    $constructionSpecification = $_POST['construction_specification'];
    $designerCertificate = $_POST['designer_certificate'];
    $supervisionDeclaration = $_POST['supervision_declaration'];
    $electricityStatement = $_POST['electricity_statement'];
    $waterStatement = $_POST['water_statement'];
    $gasStatement = $_POST['gas_statement'];
    $telecommunicationsStatement = $_POST['telecommunications_statement'];
    $roadStatement = $_POST['road_statement'];
    $trafficStatement = $_POST['traffic_statement'];
    $environmentalStatement = $_POST['environmental_statement'];
    $supportingDocument = $_POST['supporting_document'];

    $pdoManager->submitApplication($role, $street, $houseNumber, $city, $postCode,
        $country, $parcelNumber, $zoningDistrict, $currentUse,
        $proposedUse, $projectTitle, $projectDescription, $projectType, $estimatedTime, $estimatedCost,
        $territorialDecision, $ownershipOrContractor, $constructionSpecification, $projectDocumentation,
        $designerCertificate, $supervisionDeclaration, $electricityStatement, $waterStatement,
        $gasStatement, $roadStatement, $trafficStatement, $environmentalStatement, $supportingDocument);

    header("Location: ../dashboard/user_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/submitApplicationForm.css">
    <title>Submit New Application</title>
</head>
<body>
<div class="menu">
    <a href="../dashboard/user_dashboard.php">View Dashboard</a>
    <a href="viewProfile.php">View Profile</a>
    <a href="submitApplication.php">Submit Application</a>
    <a href="viewApplications.php">View Applications</a>
    <a href="viewObjections.php">View Objections</a>
    <a href="../authentication/logout.php">Log Out</a>
</div>
<form id="multi_step_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <!-- Step 1: Select Role -->
    <div id="step_role" class="form-section active">
        <h2>Select Role</h2>
        <label>
            <input type="radio" name="selected_role" value="owner"> Owner
        </label>
        <label>
            <input type="radio" name="selected_role" value="contractor"> Contractor
        </label>
        <br>
        <button type="button" id="next_role">Next</button>
    </div>

    <!-- Step 2: Property Information -->
    <div id="step_property" class="form-section">
        <h2>Property Information</h2>
        <label for="street">Street:</label>
        <input type="text" id="street" name="street" required>
        <br>
        <label for="house_number">House number:</label>
        <input type="text" id="house_number" name="house_number" required>
        <br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" required>
        <br>
        <label for="post_code">Post code:</label>
        <input type="text" id="post_code" name="post_code" required>
        <br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" required>
        <br>
        <label for="parcel_number">Parcel number:</label>
        <input type="text" id="parcel_number" name="parcel_number" required>
        <br>
        <label for="zoning_district">Zoning district:</label>
        <input type="text" id="zoning_district" name="zoning_district" required>
        <br>
        <label for="current_use">Current use:</label>
        <input type="text" id="current_use" name="current_use" required>
        <br>
        <label for="proposed_use">Proposed use:</label>
        <input type="text" id="proposed_use" name="proposed_use" required>
        <br>
        <button type="button" id="prev_property">Previous</button>
        <button type="button" id="next_property">Next</button>
    </div>

    <!-- Step 3: Project Information -->
    <div id="step_project" class="form-section">
        <h2>Project Information</h2>
        <label for="project_title">Project title:</label>
        <input type="text" id="project_title" name="project_title" required>
        <br>
        <label for="project_description">Project description:</label>
        <input type="text" id="project_description" name="project_description" required>
        <br>
        <label for="project_type">Project type:</label>
        <select id="project_type" name="project_type" required>
            <option value="">Select type</option>
            <option value="construction">Construction</option>
            <option value="renovation">Renovation</option>
            <option value="demolition">Demolition</option>
        </select>
        <br>
        <label for="estimated_time">Estimated time (in months):</label>
        <input type="text" id="estimated_time" name="estimated_time" required>
        <br>
        <label for="estimated_cost">Estimated cost (in €):</label>
        <input type="text" id="estimated_cost" name="estimated_cost" required>
        <br>
        <button type="button" id="prev_project">Previous</button>
        <button type="button" id="next_project">Next</button>
    </div>

    <!-- Step 4: Documents -->
    <div id="step_documents" class="form-section">
        <h2>Documents</h2>
        <label for="territorial_decision">Territorial decision:</label>
        <input type="text" id="territorial_decision" name="territorial_decision" required>
        <br>
        <label for="ownership_document_or_contractor_agreement">Legal document (ownership/contractorship):</label>
        <input type="text" id="ownership_document_or_contractor_agreement" name="ownership_document_or_contractor_agreement" required>
        <br>
        <label for="project_documentation">Project documentation:</label>
        <input type="text" id="project_documentation" name="project_documentation" required>
        <br>
        <label for="construction_specification">Construction specification:</label>
        <input type="text" id="construction_specification" name="construction_specification" required>
        <br>
        <label for="designer_certificate">Certificate of competence of the designer:</label>
        <input type="text" id="designer_certificate" name="designer_certificate" required>
        <br>
        <label for="supervision_declaration">Declaration of construction supervision:</label>
        <input type="text" id="supervision_declaration" name="supervision_declaration" required>
        <br>
        <label for="electricity_statement">Statement from electricity networks administrator:</label>
        <input type="text" id="electricity_statement" name="electricity_statement" required>
        <br>
        <label for="water_statement">Statement from water networks administrator:</label>
        <input type="text" id="water_statement" name="water_statement" required>
        <br>
        <label for="gas_statement">Statement from gas networks administrator:</label>
        <input type="text" id="gas_statement" name="gas_statement" required>
        <br>
        <label for="telecommunications_statement">Statement from telecommunications networks administrator:</label>
        <input type="text" id="telecommunications_statement" name="telecommunications_statement" required>
        <br>
        <label for="road_statement">Statement from road administrator:</label>
        <input type="text" id="road_statement" name="road_statement" required>
        <br>
        <label for="traffic_statement">Statement from traffic inspectorate:</label>
        <input type="text" id="traffic_statement" name="traffic_statement" required>
        <br>
        <label for="environmental_statement">Statement from environmental authority:</label>
        <input type="text" id="environmental_statement" name="environmental_statement" required>
        <br>
        <label for="supporting_document">Supporting documents:</label>
        <input type="text" id="supporting_document" name="supporting_document" required>
        <br>
        <button type="button" id="prev_documents">Previous</button>
        <button type="submit">Submit</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('multi_step_form');
        const nextRoleBtn = document.getElementById('next_role');
        const prevPropertyBtn = document.getElementById('prev_property');
        const nextPropertyBtn = document.getElementById('next_property');
        const prevProjectBtn = document.getElementById('prev_project');
        const nextProjectBtn = document.getElementById('next_project');
        const prevDocumentsBtn = document.getElementById('prev_documents');

        nextRoleBtn.addEventListener('click', function() {
            const selectedRole = document.querySelector('input[name="selected_role"]:checked');
            if (selectedRole) {
                document.getElementById('step_role').classList.remove('active');
                document.getElementById('step_property').classList.add('active');
            } else {
                alert('Please select a role.');
            }
        });

        prevPropertyBtn.addEventListener('click', function() {
            document.getElementById('step_property').classList.remove('active');
            document.getElementById('step_role').classList.add('active');
        });

        nextPropertyBtn.addEventListener('click', function() {
            const propertyFields = document.querySelectorAll('#step_property input');
            if (validateFields(propertyFields)) {
                document.getElementById('step_property').classList.remove('active');
                document.getElementById('step_project').classList.add('active');
            } else {
                alert('Please fill in all fields.');
            }
        });

        prevProjectBtn.addEventListener('click', function() {
            document.getElementById('step_project').classList.remove('active');
            document.getElementById('step_property').classList.add('active');
        });

        nextProjectBtn.addEventListener('click', function() {
            const projectFields = document.querySelectorAll('#step_project input, #step_project select');
            if (validateFields(projectFields)) {
                document.getElementById('step_project').classList.remove('active');
                document.getElementById('step_documents').classList.add('active');
            } else {
                alert('Please fill in all fields.');
            }
        });

        prevDocumentsBtn.addEventListener('click', function() {
            document.getElementById('step_documents').classList.remove('active');
            document.getElementById('step_project').classList.add('active');
        });

        function validateFields(fields) {
            for (let i = 0; i < fields.length; i++) {
                if (fields[i].value.trim() === '') {
                    return false;
                }
            }
            return true;
        }
    });
</script>
</body>
</html>


