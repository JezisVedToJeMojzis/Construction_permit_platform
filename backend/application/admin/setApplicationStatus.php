<?php
session_start();
require_once "../../PDO/PDOAdminManager.php";

$config = require_once "../../config.php";

if (!isset($config["servername"], $config["username"], $config["password"], $config["database"])) {
    die("Configuration error: Missing database credentials.");
}

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

header('Content-Type: application/json');

try {
    $pdoManager = new PDOAdminManager($serverName, $userName, $userPassword, $databaseName);

    $applicationId = $_GET['application_id'];
    $statusId = $_GET['status_id'];

    $pdoManager->setApplicationStatus($applicationId, $statusId);

   // header("Location: ../../frontend/dashboard/user_dashboard.php");
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}

?>
