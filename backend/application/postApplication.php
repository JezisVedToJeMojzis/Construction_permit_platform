<?php
session_start();
require_once "../PDO/PDOApplicationManager.php";

$config = require_once "../config.php";

if (!isset($config["servername"], $config["username"], $config["password"], $config["database"])) {
    die("Configuration error: Missing database credentials.");
}

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOApplicationManager($serverName, $userName, $userPassword, $databaseName);

    $accountId = $_SESSION["account_id"] ?? null;
    if (!$accountId) {
        die("Error: User session expired or invalid.");
    }

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
    $architecturalPlan = $_POST['architectural_plan'];
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

    $pdoManager->submitApplication($accountId, $role, $street, $houseNumber, $city, $postCode,
        $country, $parcelNumber, $zoningDistrict, $currentUse,
        $proposedUse, $projectTitle, $projectDescription, $projectType, $estimatedTime, $estimatedCost,
        $territorialDecision, $ownershipOrContractor, $constructionSpecification, $projectDocumentation,
        $architecturalPlan, $designerCertificate, $supervisionDeclaration, $electricityStatement, $waterStatement,
        $gasStatement, $telecommunicationsStatement, $roadStatement, $trafficStatement, $environmentalStatement, $supportingDocument);

    header("Location: ../../frontend/dashboard/user_dashboard.php");
    exit();
}
?>