<?php
session_start();
require_once "../PDO/PDORegisterManager.php";

$config = require_once "../config.php";
$serverName = $config["servername"];
$userName = $config["username"];
$userPassword = $config["password"];
$databaseName = $config["database"];

$email = $password = $role = $first_name = $last_name = $org_name = $contact_first_name = $contact_last_name = '';
$phone_number = $street = $house_number = $city = $post_code = $country = $identification_number = $registration_number = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdoManager = new PDORegisterManager($serverName, $userName, $userPassword, $databaseName);

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate role selection
    if (empty($role)) {
        $error_message = "Please select a role (Private Person or Organization).";
    } elseif ($role === 'private') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone_number = $_POST['phone_number'];
        $street = $_POST['street'];
        $house_number = $_POST['house_number'];
        $city = $_POST['city'];
        $post_code = $_POST['post_code'];
        $country = $_POST['country'];
        $identification_number = $_POST['identification_number'];
        // Check if any field is empty
        if (empty($first_name) || empty($last_name) || empty($phone_number) || empty($street) ||
            empty($house_number) || empty($city) || empty($post_code) || empty($country) || empty($identification_number)) {
            $error_message = "Please fill in all fields.";
        } else {
            $pdoManager->registerPrivateAccount($email, $password, $role, $first_name, $last_name,
                $phone_number, $street, $house_number, $city, $post_code,
                $country, $identification_number);
            header("Location: login.php");
            exit();
        }
    } elseif ($role === 'organization') {
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
        // Check if any field is empty
        if (empty($org_name) || empty($contact_first_name) || empty($contact_last_name) || empty($phone_number) ||
            empty($street) || empty($house_number) || empty($city) || empty($post_code) || empty($country) ||
            empty($registration_number)) {
            $error_message = "Please fill in all fields.";
        } else {
            $pdoManager->registerOrganizationAccount($email, $password, $role, $org_name, $contact_first_name,
                $contact_last_name, $phone_number, $street, $house_number,
                $city, $post_code, $country, $registration_number);
            header("Location: login.php");
            exit();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../frontend/styles.css">
    <title>Registration</title>
    <script>
        // Function to show fields based on role selection
        function showFields() {
            var role = document.getElementById('role').value;
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
        }

        // Run initially and on role change
        document.addEventListener('DOMContentLoaded', showFields);
        document.getElementById('role').addEventListener('change', showFields);
    </script>
</head>
<body>
<div class="menu">
    <a href="login.php" class="login-btn">Login</a>
</div>
<div class="content">
    <h2>Registration Form</h2>
    <?php if (!empty($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="">Choose Role</option>
            <option value="private" <?php if ($role === 'private') echo 'selected'; ?>>Private Person</option>
            <option value="organization" <?php if ($role === 'organization') echo 'selected'; ?>>Organization</option>
        </select>
        <br>
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
</div>
</body>
</html>

<script>
    // Function to show fields based on role selection
    function showFields() {
        var role = document.getElementById('role').value;
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
    }

    // Run initially and on role change
    document.addEventListener('DOMContentLoaded', showFields);
    document.getElementById('role').addEventListener('change', showFields);

    // Validate form before submission
    function validateForm() {
        var role = document.getElementById('role').value;

        if (role === '') {
            alert('Please select a role.');
            return false;
        }

        // Validate based on role
        if (role === 'private') {
            var first_name = document.getElementById('first_name').value.trim();
            var last_name = document.getElementById('last_name').value.trim();
            var phone_number = document.getElementById('phone_number').value.trim();
            var street = document.getElementById('street').value.trim();
            var house_number = document.getElementById('house_number').value.trim();
            var city = document.getElementById('city').value.trim();
            var post_code = document.getElementById('post_code').value.trim();
            var country = document.getElementById('country').value.trim();
            var identification_number = document.getElementById('identification_number').value.trim();

            if (first_name === '' || last_name === '' || phone_number === '' || street === '' ||
                house_number === '' || city === '' || post_code === '' || country === '' ||
                identification_number === '') {
                alert('Please fill in all fields for Private Person.');
                return false;
            }
        }
        if (role === 'organization') {
            var org_name = document.getElementById('org_name').value.trim();
            var contact_first_name = document.getElementById('contact_first_name').value.trim();
            var contact_last_name = document.getElementById('contact_last_name').value.trim();
            var phone_number_org = document.getElementById('phone_number_org').value.trim();
            var street_org = document.getElementById('street_org').value.trim();
            var house_number_org = document.getElementById('house_number_org').value.trim();
            var city_org = document.getElementById('city_org').value.trim();
            var post_code_org = document.getElementById('post_code_org').value.trim();
            var country_org = document.getElementById('country_org').value.trim();
            var registration_number = document.getElementById('registration_number').value.trim();

            if (org_name === '' || contact_first_name === '' || contact_last_name === '' || phone_number_org === '' ||
                street_org === '' || house_number_org === '' || city_org === '' || post_code_org === '' ||
                country_org === '' || registration_number === '') {
                alert('Please fill in all fields for Organization.');
                return false;
            }
        }

        return true;
    }
</script>
