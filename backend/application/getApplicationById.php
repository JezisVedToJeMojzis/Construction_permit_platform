<?php
session_start();
require_once "../PDO/PDOApplicationManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

header('Content-Type: application/json');

$applicationId = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;

try {
    $pdoManager = new PDOApplicationManager($serverName, $userName, $userPassword, $databaseName);

    // Get the application by ID
    $application = $pdoManager->getApplicationById($applicationId);

    echo json_encode($application);

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
