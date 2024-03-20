<?php
// admin_create_card.php
session_start();
if (!isAdmin()) {
    die("Unauthorized access!");
}

$errors = [];
$cards = json_decode(file_get_contents("cards.json"), true);

$name = $_POST['name'] ?? '';
$type = $_POST['type'] ?? '';
$hp = $_POST['hp'] ?? '';
$attack = $_POST['attack'] ?? '';
$defense = $_POST['defense'] ?? '';
$price = $_POST['price'] ?? '';
$description = $_POST['description'] ?? '';
$image = $_POST['image'] ?? '';

if ($_POST) {
    if (empty($name)) {
        $errors['name'] = 'Card name is required!';
    } elseif (strlen($name) < 3) {
        $errors['name'] = 'Card name must be at least 3 characters long!';
    }
    if (empty($type)) {
        $errors['type'] = 'Card type is required!';
    }

    foreach (['hp' => $hp, 'attack' => $attack, 'defense' => $defense, 'price' => $price] as $key => $value) {
        if (empty($value)) {
            $errors[$key] = ucfirst($key) . ' is required!';
        } elseif (!is_numeric($value) || (int)$value <= 0) {
            $errors[$key] = ucfirst($key) . ' must be a positive integer!';
        }
    }
    if (empty($description)) {
        $errors['description'] = 'Description is required!';
    } elseif (strlen($description) < 10) {
        $errors['description'] = 'Description must be at least 10 characters long!';
    }
    if (empty($image)) {
        $errors['image'] = 'Image URL is required!';
    }

    if (!$errors) {
        $new_card = [
            "name" => $name,
            "type" => $type,
            "hp" => $hp,
            "attack" => $attack,
            "defense" => $defense,
            "price" => $price,
            "description" => $description,
            "image" => $image,
            "owner" => "admin"
        ];

        $cards[] = $new_card;
        file_put_contents("cards.json", json_encode($cards, JSON_PRETTY_PRINT));

        header("Location: user_details.php"); // Redirect to admin panel or confirmation page
        exit();
    }
}

function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['username'] === 'admin';
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create card</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include_once "navigation.php" ?>

    <div class="create-card-page">
        <?php if (isAdmin()) : ?>
            <form action="admin_create_card.php" method="post" novalidate>
                <h1>Create a Card</h1>
                <label for="name">Card Name:</label>
                <input type="text" name="name" id="name" value="<?= $name ?>"> <span class="error"><?= $errors['name'] ?? '' ?></span><br>
                <label for="type">Card Type:</label>
                <input type="text" name="type" id="type" value="<?= $type ?>"> <span class="error"><?= $errors['type'] ?? '' ?></span><br>
                <label for="hp">HP:</label>
                <input type="text" name="hp" id="hp" value="<?= $hp ?>"> <span class="error"><?= $errors['hp'] ?? '' ?></span><br>
                <label for="attack">Attack:</label>
                <input type="text" name="attack" id="attack" value="<?= $attack ?>"> <span class="error"><?= $errors['attack'] ?? '' ?></span><br>
                <label for="defense">Defense:</label>
                <input type="text" name="defense" id="defense" value="<?= $defense ?>"> <span class="error"><?= $errors['defense'] ?? '' ?></span><br>
                <label for="price">Price:</label>
                <input type="text" name="price" id="price" value="<?= $price ?>"> <span class="error"><?= $errors['price'] ?? '' ?></span><br>
                <label for="description">Description:</label>
                <textarea name="description" id="description"><?= $description ?></textarea> <span class="error"><?= $errors['description'] ?? '' ?></span><br>
                <label for="image">Image URL:</label>
                <input type="text" name="image" id="image" value="<?= $image ?>"> <span class="error"><?= $errors['image'] ?? '' ?></span><br>
                <button id="create-card-button" type="submit">Create Card</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>