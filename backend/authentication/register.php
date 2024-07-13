<?php
session_start();
require_once "../PDO/PDORegisterAccountManager.php";

$config = require_once "../config.php";
$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

$email = $password = $role = $first_name = $last_name = $org_name = $contact_first_name = $contact_last_name = '';
$phone_number = $street = $house_number = $city = $post_code = $country = $identification_number = $registration_number = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDORegisterAccountManager($serverName, $userName, $userPassword, $databaseName);
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role === 'private'){
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
    elseif ($role === 'organization') {
        $org_name = $_POST['org_name'];
        $contact_first_name = $_POST['contact_first_name'];
        $contact_last_name = $_POST['contact_last_name'];
        $phone_number = $_POST['phone_number'];
        $street = $_POST['street'];
        $house_number = $_POST['house_number'];
        $city = $_POST['city'];
        $post_code = $_POST['post_code'];
        $country = $_POST['country'];
        $registration_number = $_POST['registration_number'];
        $pdoManager->registerOrganizationAccount($email, $password, $role, $org_name, $contact_first_name,
            $contact_last_name, $phone_number, $street, $house_number,
            $city, $post_code, $country, $registration_number);
    }

    header("Location: login.php");
}
else {
    header("HTTP/1.1 404 Not Found");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
<h2>Registration Form</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="private" <?php if ($role === 'private') echo 'selected'; ?>>Private Person</option>
        <option value="organization" <?php if ($role === 'organization') echo 'selected'; ?>>Organization</option>
    </select>
    <br>
    <!-- Additional fields based on role -->
    <!-- For private persons -->
    <div id="private_fields" style="display: <?php echo ($role === 'private') ? 'block' : 'none'; ?>">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
        <br>
        <label for="street">Street:</label>
        <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($street); ?>">
        <br>
        <label for="house_number">House Number:</label>
        <input type="text" id="house_number" name="house_number" value="<?php echo htmlspecialchars($house_number); ?>">
        <br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>">
        <br>
        <label for="post_code">Post Code:</label>
        <input type="text" id="post_code" name="post_code" value="<?php echo htmlspecialchars($post_code); ?>">
        <br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>">
        <br>
        <label for="identification_number">Identification Number:</label>
        <input type="text" id="identification_number" name="identification_number" value="<?php echo htmlspecialchars($identification_number); ?>">
        <br>
    </div>
    <!-- For organizations -->
    <div id="organization_fields" style="display: <?php echo ($role === 'organization') ? 'block' : 'none'; ?>">
        <label for="org_name">Organization Name:</label>
        <input type="text" id="org_name" name="org_name" value="<?php echo htmlspecialchars($org_name); ?>">
        <br>
        <label for="contact_first_name">Contact Person First Name:</label>
        <input type="text" id="contact_first_name" name="contact_first_name" value="<?php echo htmlspecialchars($contact_first_name); ?>">
        <br>
        <label for="contact_last_name">Contact Person Last Name:</label>
        <input type="text" id="contact_last_name" name="contact_last_name" value="<?php echo htmlspecialchars($contact_last_name); ?>">
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
        <br>
        <label for="street">Street:</label>
        <input type="text" id="street" name="street" value="<?php echo htmlspecialchars($street); ?>">
        <br>
        <label for="house_number">House Number:</label>
        <input type="text" id="house_number" name="house_number" value="<?php echo htmlspecialchars($house_number); ?>">
        <br>
        <label for="city">City:</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>">
        <br>
        <label for="post_code">Post Code:</label>
        <input type="text" id="post_code" name="post_code" value="<?php echo htmlspecialchars($post_code); ?>">
        <br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>">
        <br>
        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" value="<?php echo htmlspecialchars($registration_number); ?>">
        <br>
    </div>
    <br>
    <button type="submit">Register</button>
</form>
<script>
    // Script to show/hide fields based on role selection
    document.getElementById('role').addEventListener('change', function() {
        var role = this.value;
        var privateFields = document.getElementById('private_fields');
        var organizationFields = document.getElementById('organization_fields');

        if (role === 'private') {
            privateFields.style.display = 'block';
            organizationFields.style.display = 'none';
        } else if (role === 'organization') {
            privateFields.style.display = 'none';
            organizationFields.style.display = 'block';
        } else {
            privateFields.style.display = 'none';
            organizationFields.style.display = 'none';
        }
    });
</script>
</body>
</html>