<?php
require_once "../PDO/PDOCommentManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $applicationId = $_GET['application_id'];

    $pdoManager = new PDOCommentManager($serverName, $userName, $userPassword, $databaseName);
    $comments = $pdoManager->getAllApplicationCommentsByApplicationId($applicationId);
    echo json_encode($comments);

    exit();
}
?>
