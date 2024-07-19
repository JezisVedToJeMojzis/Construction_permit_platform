<?php
session_start();
require_once "../PDO/PDOCommentManager.php";

$config = require_once "../config.php";

$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

//header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOCommentManager($serverName, $userName, $userPassword, $databaseName);

    $applicationId = $_POST['application_id'];
    $comment = $_POST['comment'];
    $accountId = $_SESSION['account_id'];

    $pdoManager->postApplicationComment($applicationId, $accountId, $comment);

    //header("Location: ../../frontend/applicationDetails.html?id=$applicationId");
    exit();
}
?>
