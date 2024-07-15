<?php
session_start();
require_once "../PDO/PDORegisterManager.php";

$config = require_once "../config.php";
$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDORegisterManager($serverName, $userName, $userPassword, $databaseName);

    // Extract common fields
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // organization registration
    if ($role === 'organization') {
        $org_name = $_POST['org_name'];
        $contact_first_name = $_POST['contact_first_name'];
        $contact_last_name = $_POST['contact_last_name'];
        $phone_number = $_POST['phone_number_org'];
        $street = $_POST['street_org'];
        $house_number = $_POST['house_number_org'];
        $city = $_POST['city_org'];
        $post_code = $_POST['post_code_org'];
        $country = $_POST['country_org'];
        $registration_number = $_POST['registration_number'];

        $pdoManager->registerOrganizationAccount($email, $password, $role, $org_name, $contact_first_name,
            $contact_last_name, $phone_number, $street, $house_number,
            $city, $post_code, $country, $registration_number);
    }

    // private person registration
    else if ($role === 'private') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $street = $_POST['street'];
        $house_number = $_POST['house_number'];
        $city = $_POST['city'];
        $post_code = $_POST['post_code'];
        $country = $_POST['country'];
        $identification_number = $_POST['identification_number'];

        $pdoManager->registerPrivateAccount($email, $password, $role, $first_name, $last_name,
            $phone_number, $street, $house_number, $city, $post_code,
            $country, $identification_number);
    }

    // Redirect to login page after successful registration
    header("Location: ../../frontend/authentication/login.html");
    exit();
}
?>
