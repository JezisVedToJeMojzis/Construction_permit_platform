<?php
session_start();
require_once "../PDO/PDOLogManager.php";

$config = require_once "../config.php";

if (!isset($config["servername"], $config["username"], $config["password"], $config["database"])) {
    die("Configuration error: Missing database credentials.");
}

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $objectionId = $_GET['objection_id'];

    $pdoManager = new PDOLogManager($serverName, $userName, $userPassword, $databaseName);
    $logs = $pdoManager->getAllLogsByObjectionId($objectionId);
    echo json_encode($logs);

    exit();
}

?>
