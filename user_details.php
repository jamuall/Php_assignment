<?php
if (!isset($_SESSION)) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Assuming $_SESSION['user']['username'] contains the logged-in user's name
$loggedInUsername = $_SESSION['user']['username'];

// Read all cards
$all_cards = json_decode(file_get_contents("cards.json"), true);

// Filter cards to get only those owned by the logged-in user
$user_card_details = array_filter($all_cards, function ($card) use ($loggedInUsername) {
    return $card['owner'] === $loggedInUsername;
});

// Extract card IDs for the user's cards
$user_cards = array_keys($user_card_details);

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
    <title>User Details</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include_once "navigation.php"; ?>
    <div class="main-content">
        <div class="user-details">
            <h2>User: <?= $_SESSION["user"]["username"] ?></h2>
            <p>Email address: <?= $_SESSION["user"]["email"] ?></p>
            <p>Balance: <?= $_SESSION["user"]["money"] ?> coins</p>
        </div>
        <h2 style="text-align: center;">My cards</h2>
        <div class="user-cards">
            <?php if (empty($user_card_details)) : ?>
                <p>You don't have any cards right now.</p>
            <?php else : ?>
                <?php foreach ($user_card_details as $card) : ?>
                    <div class="card">
                        <img src="<?= $card['image'] ?>" alt="<?= ($card['name']) ?>">
                        <h3><?= ($card["name"]) ?></h3>
                        <p>Type: <?= ($card["type"]) ?></p>
                        <p>❤️ <?= ($card['hp']) ?>, ⚔️ <?= ($card['attack']) ?>, 🛡️ <?= ($card['defense']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (isAdmin()) : ?>
            <div class="admin-actions">
                <a href="admin_create_card.php" class="create-card-button">Create New Card</a>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>