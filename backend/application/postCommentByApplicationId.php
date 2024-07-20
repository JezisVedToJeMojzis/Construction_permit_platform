<?php
session_start();
require_once "../PDO/PDOCommentManager.php";

$config = require_once "../config.php";

if (!isset($config["servername"], $config["username"], $config["password"], $config["database"])) {
    die("Configuration error: Missing database credentials.");
}

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

//header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOCommentManager($serverName, $userName, $userPassword, $databaseName);

    $applicationId = $_POST['application_id'];
    $comment = $_POST['comment'];
    $accountId = $_SESSION["account_id"] ?? null;
    if (!$accountId) {
        die("Error: User session expired or invalid.");
    }


    $pdoManager->postApplicationComment($applicationId, $accountId, $comment);

    //header("Location: ../../frontend/applicationDetails.php?id=$applicationId");
    exit();
}
?>
