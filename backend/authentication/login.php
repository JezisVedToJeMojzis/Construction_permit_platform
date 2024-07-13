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
        echo '<pre>';
        print_r($user);
        echo '</pre>';
        // User found, verify password
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['account_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect to dashboard based on role
            switch ($user['role']) {
                case 'private':
                    header("Location: ../dashboard/private_and_organization_dashboard.php");
                    exit();
                case 'organization':
                    header("Location: ../dashboard/private_and_organization_dashboard.php");
                    exit();
                case 'admin':
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
    <title>Login</title>
</head>
<body>
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
</body>
</html>
