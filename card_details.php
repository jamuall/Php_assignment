<?php
session_start();

// Function to get the card data by ID (assuming your data structure is the same)

// if (!isset($_SESSION['user'])) {
//     header("Location: login.php");
//     exit();
// }

function getCardById($cards, $cardId)
{
    foreach ($cards as $id => $card) {
        if ($id == $cardId) {
            return $card;
        }
    }
    return null;
}

// Check if card ID is provided in the URL
if (isset($_GET['id'])) {
    $cardId = $_GET['id'];

    // Load all cards (you may load this from your data source, e.g., cards.json)
    $cards = json_decode(file_get_contents("cards.json"), true);

    // Get the card data by ID
    $card = getCardById($cards, $cardId);

    $backgroundColor = '';
    if ($card['type'] == 'fire') {
        $backgroundColor = 'maroon';
    } elseif ($card['type'] == 'electric') {
        $backgroundColor = 'yellow';
    } elseif ($card['type'] == 'grass') {
        $backgroundColor = 'green';
    } elseif ($card['type'] == 'normal') {
        $backgroundColor = 'gray';
    } elseif ($card['type'] == 'water') {
        $backgroundColor = 'deepskyblue';
    } elseif ($card['type'] == 'bug') {
        $backgroundColor = 'darkolivegreen';
    } elseif ($card['type'] == 'poison') {
        $backgroundColor = 'orangered';
    }
    // ... Add more color mappings for other types ...

} else {
    // Redirect to main page if card ID is not provided
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $card['name'] ?> Details</title>
    <link rel="stylesheet" href="style.css">
</head>

<body style="background-color: <?= $backgroundColor ?>;">
    <?php include_once "navigation.php"; ?>
    <div class="container">
        <div class="card-detail">
            <img src="<?= $card['image'] ?>" alt="<?= $card['name'] ?>">
            <div class="card-info" style="background-color: #f9f7f7;; border-radius: 25px;">
                <h3 class="card-name"><?= $card['name'] ?></h3>
                <p class="card-stats">Type: <?= $card['type'] ?></p>
                <p class="card-stats">HP: <?= $card['hp'] ?> ❤️</p>
                <p class="card-stats">Attack: <?= $card['attack'] ?> ⚔️</p>
                <p class="card-stats">Defense: <?= $card['defense'] ?> 🛡️</p>
                <p class="card-stats">Price: <?= $card['price'] ?> 💰</p>
                <p class="card-description">Description: <?= $card['description'] ?></p>
            </div>
        </div>
    </div>
</body>

</html>