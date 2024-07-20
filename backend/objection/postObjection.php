<?php
session_start();
require_once "../PDO/PDOObjectionManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOObjectionManager($serverName, $userName, $userPassword, $databaseName);

    $accountId = $_SESSION["account_id"];
    $applicationId = $_SESSION["application_id"];
    $briefSummary = $_POST["brief_summary"];
    $detailedExplanation = $_POST["detailed_explanation"];
    $affectedParties = $_POST["affected_parties"];
    $supportingDocument = $_POST["supporting_documents"];

    $pdoManager->submitObjection($accountId, $briefSummary, $detailedExplanation, $affectedParties, $supportingDocument);

    header("Location: ../../frontend/dashboard/user_dashboard.php");
    exit();
}
?>