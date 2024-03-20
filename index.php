<?php
session_start();

$cards = json_decode(file_get_contents("cards.json"), true);

function isUserLoggedIn()
{
    return isset($_SESSION['user']);
}

function buyCard($cardId)
{
    if (isUserLoggedIn()) {
        // Logic to buy the card
        // Subtract money from user's account and add the card to their collection
        header("Location: index.php"); // Redirect to a success page or similar
    } else {
        header("Location: login.php"); // Redirect to login page
    }
    exit();
}

// Check if there's a buy request

if (isset($_GET['buy']) && isset($cards[$_GET['buy']])) {
    buyCard($_GET['buy']);
}
function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['username'] === 'admin';
}
function getUserByUsername($username)
{
    $users = json_decode(file_get_contents("users.json"), true);
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    return null;
}

function canUserBuyCard($user, $card)
{
    $cardLimit = 5;
    return $user['money'] >= $card['price'] && count($user['cards']) < $cardLimit;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeShop</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <?php include_once "navigation.php" ?>
    <div class="card-container">
        <?php foreach ($cards as $id => $card) : ?>
            <?php if ($card['owner'] === 'admin') : ?>
                <div class="card">
                    <img src="<?= $card['image'] ?>" alt="<?= $card['name'] ?>">
                    <a href="card_details.php?id=<?= $id ?>">
                        <h3><?= $card['name'] ?></h3>
                    </a>
                    <p>Type: <?= $card['type'] ?></p>
                    <p>❤️ <?= $card['hp'] ?>, ⚔️ <?= $card['attack'] ?>, 🛡️ <?= $card['defense'] ?></p>
                    <p>💰 <?= $card['price'] ?> coins</p>
                    <?php if ($card['owner'] === 'admin' && !isAdmin() && isUserLoggedIn()) : ?>
                        <a class="buy-button" href="buy_card.php?buy=<?= $id ?>">Buy</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>

</html>