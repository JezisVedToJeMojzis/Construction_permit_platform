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

try {
    $pdoManager = new PDOObjectionManager($serverName, $userName, $userPassword, $databaseName);

    $objectionId = $_GET['objection_id'];

    echo $pdoManager->withdrawObjection($objectionId);

    header("Location: ../../frontend/dashboard/user_dashboard.php");
    exit();

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
