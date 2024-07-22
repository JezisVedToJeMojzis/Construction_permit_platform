<?php
session_start();
require_once "../PDO/PDOLoginManager.php";

$config = require_once "../config.php";
$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pdoManager = new PDOLoginManager($serverName, $userName, $userPassword, $databaseName);
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (isset($error_message)){
        echo $error_message;
    }

    $user = $pdoManager->checkEmail($email);

    if ($user) {
        // User found, verify password
        if ($password === $user['password']) {
            $_SESSION['user'] = $user;
            $_SESSION['account_id'] = $user['id'];
            $_SESSION['account_email'] = $user['email'];
            $_SESSION['account_role'] = $user['role'];

            // Redirect to dashboard based on role
            switch ($user['role']) {
                case 'private':
                    $_SESSION['first_name'] = $user['pp_first_name'];
                    $_SESSION['last_name'] = $user['pp_last_name'];
                    $_SESSION['phone_number'] = $user['pp_phone_number'];
                    $_SESSION['street'] = $user['pp_street'];
                    $_SESSION['house_number'] = $user['pp_house_number'];
                    $_SESSION['city'] = $user['pp_city'];
                    $_SESSION['post_code'] = $user['pp_post_code'];
                    $_SESSION['country'] = $user['pp_country'];
                    $_SESSION['identification_number'] = $user['pp_identification_number'];
                    header("Location: ../../frontend/dashboard/user_dashboard.php");
                    exit();
                case 'organization':
                    $_SESSION['organization_name'] = $user['oa_name'];
                    $_SESSION['contact_first_name'] = $user['oa_contact_first_name'];
                    $_SESSION['contact_last_name'] = $user['oa_contact_last_name'];
                    $_SESSION['phone_number'] = $user['oa_phone_number'];
                    $_SESSION['street'] = $user['oa_street'];
                    $_SESSION['house_number'] = $user['oa_house_number'];
                    $_SESSION['city'] = $user['oa_city'];
                    $_SESSION['post_code'] = $user['oa_post_code'];
                    $_SESSION['country'] = $user['oa_country'];
                    $_SESSION['registration_number'] = $user['oa_registration_number'];
                    header("Location: ../../frontend/dashboard/user_dashboard.php");
                    exit();
                case 'admin':
                    $_SESSION['admin_id'] = $user['aa_id'];
                    $_SESSION['first_name'] = $user['aa_first_name'];
                    $_SESSION['last_name'] = $user['aa_last_name'];
                    header("Location: ../../frontend/dashboard/admin_dashboard.php");
                    exit();
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
}
?>
