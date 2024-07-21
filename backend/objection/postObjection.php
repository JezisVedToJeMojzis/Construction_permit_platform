<?php
session_start();
require_once "../PDO/PDOObjectionManager.php";

$config = require_once "../config.php";

if (!isset($config["servername"], $config["username"], $config["password"], $config["database"])) {
    die("Configuration error: Missing database credentials.");
}

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOObjectionManager($serverName, $userName, $userPassword, $databaseName);

    $accountId = $_SESSION["account_id"];
    $applicationId = $_POST["application_id"];
    $briefSummary = $_POST["brief_summary"];
    $detailedExplanation = $_POST["detailed_explanation"];
    $affectedParties = $_POST["affected_parties"];
    $supportingDocument = $_POST["supporting_documents"];

    $pdoManager->submitObjection($accountId, $briefSummary, $detailedExplanation, $affectedParties, $supportingDocument, $applicationId);

    header("Location: ../../frontend/dashboard/user_dashboard.php");
    exit();
}
?>