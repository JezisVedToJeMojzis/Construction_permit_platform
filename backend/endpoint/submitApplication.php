<?php
session_start();
require_once "../PDO/PDOLoginManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOApplicationManager($serverName, $userName, $userPassword, $databaseName);

    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $street = $_POST['street'];
    $house_number = $_POST['house_number'];
    $city = $_POST['city'];
    $post_code = $_POST['post_code'];
    $country = $_POST['country'];
    $parcel_number = $_POST['parcel_number'];
    $zoning_district = $_POST['zoning_district'];
    $current_use = $_POST['current_use'];
    $proposed_use = $_POST['proposed_use'];
    $project_title = $_POST['project_title'];
    $project_description = $_POST['project_description'];
    $estimated_time = $_POST['estimated_time'];
    $estimated_cost = $_POST['estimated_cost'];
    $territorial_decision = $_POST['territorial_decision'];
    $legal_document = $_POST['legal_document'];
    $construction_specification = $_POST['construction_specification'];
    $project_documentation = $_POST['project_documentation'];
    $designer = $_POST['designer'];
    $supervision = $_POST['supervision'];
    $electricity = $_POST['electricity'];
    $water = $_POST['water'];
    $gas = $_POST['gas'];
    $road = $_POST['road'];
    $traffic = $_POST['traffic'];
    $enviro = $_POST['enviro'];
    $supporting_documents = $_POST['supporting_documents'];

    $pdoManager->submitApplication($role, $street, $house_number, $city, $post_code,
        $country, $parcel_number, $zoning_district, $current_use,
        $proposed_use, $project_title, $project_description, $estimated_time, $estimated_cost,
        $territorial_decision, $legal_document, $construction_specification, $project_documentation,
        $designer, $supervision, $electricity, $water, $gas, $road, $traffic, $enviro, $supporting_documents);

    header("Location: login.php");
}
else {
    echo "Error submitting application";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Form</title>
    <style>
        .form-section {
            display: none;
            margin-bottom: 20px;
        }
        .form-section.active {
            display: block;
        }
    </style>
</head>
<body>
<form id="multi_step_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <!-- Step 1: Select Role -->
    <div id="step_role" class="form-section active">
        <h2>Select Role</h2>
        <label>
            <input type="radio" name="role" value="owner"> Owner
        </label>
        <label>
            <input type="radio" name="role" value="contractor"> Contractor
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
        <label for="legal_document">Legal document (ownership/contractorship):</label>
        <input type="text" id="legal_document" name="legal_document" required>
        <br>
        <label for="construction_specification">Construction specification:</label>
        <input type="text" id="construction_specification" name="construction_specification" required>
        <br>
        <label for="project_documentation">Project documentation:</label>
        <input type="text" id="project_documentation" name="project_documentation" required>
        <br>
        <label for="designer">Certificate of competence of the designer:</label>
        <input type="text" id="designer" name="designer" required>
        <br>
        <label for="supervision">Declaration of construction supervision:</label>
        <input type="text" id="supervision" name="supervision" required>
        <br>
        <label for="electricity">Statement from electricity networks administrator:</label>
        <input type="text" id="electricity" name="electricity" required>
        <br>
        <label for="water">Statement from water networks administrator:</label>
        <input type="text" id="water" name="water" required>
        <br>
        <label for="gas">Statement from gas networks administrator:</label>
        <input type="text" id="gas" name="gas" required>
        <br>
        <label for="road">Statement from road administrator:</label>
        <input type="text" id="road" name="road" required>
        <br>
        <label for="traffic">Statement from traffic inspectorate:</label>
        <input type="text" id="traffic" name="traffic" required>
        <br>
        <label for="enviro">Statement from environmental authority:</label>
        <input type="text" id="enviro" name="enviro" required>
        <br>
        <label for="supporting_documents">Supporting documents:</label>
        <input type="text" id="supporting_documents" name="supporting_documents" required>
        <br>
        <button type="button" id="prev_documents">Previous</button>
        <button type="submit">Submit</button>
    </div>

    <!-- Hidden input to store selected role -->
    <input type="hidden" id="selected_role" name="selected_role" value="">
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
        const selectedRoleInput = document.getElementById('selected_role');

        nextRoleBtn.addEventListener('click', function() {
            const selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole) {
                selectedRoleInput.value = selectedRole.value;
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
            const projectFields = document.querySelectorAll('#step_project input');
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

        // Function to validate all fields in a given form section
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
