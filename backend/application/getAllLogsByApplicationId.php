<?php
session_start();
require_once "../PDO/PDOLogManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $applicationId = $_GET['application_id'];

    $pdoManager = new PDOLogManager($serverName, $userName, $userPassword, $databaseName);
    $logs = $pdoManager->getAllLogsByApplicationId($applicationId);
    echo json_encode($logs);

    exit();
}

?>
