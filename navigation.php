<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["user"])) {
    $username = $_SESSION["user"]["username"];
    $balance = $_SESSION["user"]["money"];
}
?>


<nav>
    <a href="index.php">
        <h1>PokeShop</h1>
    </a>
    <div>
        <?php if (!isset($_SESSION["user"])) : ?>
            <div class="nav-action">
                <a style="color: white;" href="register.php">
                    Register
                </a>
                <a style="color: white;" href="login.php">
                    Log In
                </a>
            </div>
        <?php else : ?>
            <a style="color: white;" href="user_details.php"> <?= $username ?></a>
            <span style="color: white;">Balance: <?= $balance; ?> 💰 </span>
            <a style="color: white;" href="logout.php">Log out</a>
        <?php endif ?>
    </div>
</nav>