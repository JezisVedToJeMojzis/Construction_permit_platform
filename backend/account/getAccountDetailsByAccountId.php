<?php
session_start();
require_once "../PDO/PDOAccountManager.php";

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
    $pdoManager = new PDOAccountManager($serverName, $userName, $userPassword, $databaseName);

    $accountId = $_GET["account_id"];

    $accounts = $pdoManager->getAccountDetailsByAccountId($accountId);

} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}

header('Content-Type: application/json');
echo json_encode($accounts);
?>
