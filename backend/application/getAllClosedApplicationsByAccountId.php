<?php
session_start();
require_once "../PDO/PDOApplicationManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

header('Content-Type: application/json');

$accountId = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;

try {
    $pdoManager = new PDOApplicationManager($serverName, $userName, $userPassword, $databaseName);

    // Get the closed applications by account ID
    $applications = $pdoManager->getAllClosedApplicationsByAccountId($accountId);

    echo json_encode($applications);

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
