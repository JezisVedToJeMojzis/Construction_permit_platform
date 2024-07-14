<?php
session_start();
require_once "../PDO/PDOLoginManager.php";

$config = require_once "../config.php";
$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDOLoginManager($serverName, $userName, $userPassword, $databaseName);
    $email = $_POST['email'];
    $password = $_POST['password'];

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
                    header("Location: ../dashboard/user_dashboard.php");
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
                    header("Location: ../dashboard/user_dashboard.php");
                    exit();
                case 'admin':
                    $_SESSION['first_name'] = $user['aa_first_name'];
                    $_SESSION['last_name'] = $user['aa_last_name'];
                    header("Location: ../dashboard/admin_dashboard.php");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/styles.css">
    <title>Login</title>
</head>
<body>
<div class="menu">
    <a href="register.php" class="register-btn">Register</a>
</div>
<div class="content">
    <h2>Login</h2>
    <?php if (isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
