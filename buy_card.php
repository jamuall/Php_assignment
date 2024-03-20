<?php
session_start();

function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['username'] === 'admin';
}

function getUserByUsername($username, &$users)
{
    foreach ($users as &$user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    return null;
}

function canUserBuyCard($user, $card)
{
    $cardLimit = 5;
    $userCardCount = $user['card_count'] ?? 0;
    return $user['money'] >= $card['price'] && $userCardCount < $cardLimit;
}


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Check if a card ID is provided
if (isset($_GET['buy'])) {
    $cardId = $_GET['buy'];
    $cards = json_decode(file_get_contents("cards.json"), true);
    $card = $cards[$cardId] ?? null;

    if (!$card) {
        echo "<script>alert('Card not found.'); window.location.href = 'index.php';</script>";
        exit();
    }


    // Load all users and get current user data
    $users = json_decode(file_get_contents("users.json"), true);
    $currentUser = getUserByUsername($_SESSION['user']['username'], $users);

    // Check if user can buy the card
    if ($card['owner'] === 'admin' && canUserBuyCard($currentUser, $card)) {
        // Process purchase
        $currentUser['money'] -= $card['price'];
        $currentUser['card_count'] = ($currentUser['card_count'] ?? 0) + 1;

        $card['owner'] = $currentUser['username'];
        $cards[$cardId] = $card;

        // Save updated user and card data
        file_put_contents("users.json", json_encode($users, JSON_PRETTY_PRINT));
        file_put_contents("cards.json", json_encode($cards, JSON_PRETTY_PRINT));

        // Update session user info
        $_SESSION['user'] = $currentUser;

        // Redirect to user details page
        header("Location: user_details.php");
        exit();
    } else {
        echo "<script>alert('Cannot buy this card. You have reached the limit of 5.'); window.location.href = 'index.php';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
