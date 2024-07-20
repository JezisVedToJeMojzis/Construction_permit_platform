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

header('Content-Type: application/json');

$accountId = isset($_GET['account_id']) ? intval($_GET['account_id']) : 0;

try {
    $pdoManager = new PDOApplicationManager($serverName, $userName, $userPassword, $databaseName);

    // Get the open applications by account ID
    $applications = $pdoManager->getAllOpenApplicationsByAccountId($accountId);

    echo json_encode($applications);

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
