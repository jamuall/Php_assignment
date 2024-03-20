<?php

session_start();

$username = $_POST["username"] ?? "";
$email = $_POST["email"] ?? "";
$password1 = $_POST["password1"] ?? "";
$password2 = $_POST["password2"] ?? "";

$errors = [];
$users = json_decode(file_get_contents("users.json"), true) ?? [];

if ($_POST) {
    // Username validation
    if ($username === '') {
        $errors['username'] = 'Username is required!';
    } elseif (in_array($username, array_column($users, 'username'))) {
        $errors['username'] = 'Username already exists!';
    } else if (strlen($username) < 3) {
        $errors['username'] = "Username must be at least 3 characters long";
    }

    // Email validation
    if ($email === '') {
        $errors['email'] = 'Email is required!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format!';
    }

    // Password validation
    if ($password1 === '') {
        $errors['password1'] = 'Password is required!';
    } elseif (strlen($password1) < 5) {
        $errors['password1'] = "Password must be at least 5 characters long";
    } elseif ($password1 !== $password2) {
        $errors['password1'] = 'Passwords do not match!';
    }
    if ($password2 === '') {
        $errors['password2'] = 'Retype password !';
    } elseif ($password2 != $password1) {
        $errors['password2'] = 'Passwords do not match!';
    }

    if (!$errors) {
        $new_user = [
            "username" => $username,
            "email" => $email,
            "password" => $password1,
            "money" => 1000, // Assign initial money
            "card_count" => 0
        ];

        $users[] = $new_user;
        file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));

        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include_once "navigation.php" ?>
    <div class="register-page">
        <form action="register.php" method="post" novalidate>
            <h1>Register a New User</h1>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= $username ?>"> <span class="error"><?= $errors['username'] ?? '' ?></span> <br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= $email ?>"> <span class="error"><?= $errors['email'] ?? '' ?></span><br>
            <label for="password1">Password:</label>
            <input type="password" name="password1" id="password1"> <span class="error"><?= $errors['password1'] ?? '' ?></span><br>
            <label for="password2">Confirm Password:</label>
            <input type="password" name="password2" id="password2"> <span class="error"><?= $errors['password2'] ?? '' ?></span><br>
            <button id="register-submit-button" type="submit">Register</button>
        </form>
    </div>

</body>

</html>