<?php
session_start(); // Start the session to use session variables.

$username = $_POST["username"] ?? '';
$password = $_POST['password'] ?? '';

$errors = [];

// Check if the form was submitted
if ($_POST) {
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required.';
    }

    if (!$errors) {
        $users = json_decode(file_get_contents("users.json"), true);

        $user_from_db = current(array_filter($users, function ($element) use ($username) {
            return $element["username"] == $username;
        }));

        $user_from_db = current(array_filter($users, function ($element) use ($username) {
            return $element["username"] == $username;
        }));

        // Check if a user was found and verify the password
        if ($user_from_db !== false && $password == $user_from_db["password"]) {
            $_SESSION["user"] = $user_from_db; // Store the whole user array in the session
            header("Location: index.php");
            exit();
        } else {
            $errors['Login'] = 'Invalid username or password.';
            //header("Location: login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<body>
    <?php include_once "navigation.php" ?>
    <div class="login-page">

        <form action="login.php" method="post" novalidate>
            <h1>Login page</h1>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= $username ?>"> <span class="error"><?= $errors['username'] ?? "" ?></span><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"> <span class="error"><?= $errors['password'] ?? "" ?></span><br>
            <input type="submit" value="Login" id="submit-button"> <span class="error"><?= $errors['Login'] ?? "" ?></span><br>
        </form>
    </div>
</body>

</html>