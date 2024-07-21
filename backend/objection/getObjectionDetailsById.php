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

header('Content-Type: application/json');

$objectionId = isset($_GET['objection_id']) ? intval($_GET['objection_id']) : 0;

try {
    $pdoManager = new PDOObjectionManager($serverName, $userName, $userPassword, $databaseName);

    // Get the objection by ID
    $objection = $pdoManager->getObjectionDetailsById($objectionId);

    echo json_encode($objection);

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
